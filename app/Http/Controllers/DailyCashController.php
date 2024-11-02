<?php

namespace App\Http\Controllers;

use App\Exports\DailyCashExport;
use App\Http\Requests\DailyCashRequest;
use App\Http\Requests\DailyCashStoreRequest;
use App\Http\Requests\DailyCashUpdateRequest;
use App\Http\Resources\DailyCashResource;
use App\Http\Services\DailyCashService;
use App\Models\DailyCash;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Maatwebsite\Excel\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class DailyCashController extends Controller
{
  public function __construct(private DailyCashService $dailyCashService)
  {
  }
  /**
   * Display a listing of the resource.
   */
  public function index(Request $request)
  {
    $dailyCashes = DailyCash::query()
      ->filterByDate($request)
      ->when($request->filled('minimum_amount'), function ($query) use ($request) {
        $min_amount = $request->minimum_amount;
        $query->where('final_money', '>=', $min_amount);
      })
      ->when($request->filled('maximum_amount'), function ($query) use ($request) {
        $max_amount = $request->maximum_amount;
        $query->where('final_money', '<=', $max_amount);
      })
      ->latest()->paginate(15);
    if ($request->wantsJson()) {
      return DailyCashResource::collection($dailyCashes)->response();
    }
    return Inertia::render('views/DailyCashView', [
      'dailyCashes' => DailyCashResource::collection($dailyCashes),
    ]);
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    //
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(DailyCashStoreRequest $request)
  {
    $startMoney = $request['start_money'];
    $cashIsOpen = $this->dailyCashService->searchDailyCashCurrent();

    if (!$cashIsOpen) {
      $userId = Auth::id();
      $dailyCash = DailyCash::create(
        ['start_money' => $startMoney, 'final_money' => $startMoney, 'user_id' => $userId]
      );
      return response()->json(new DailyCashResource($dailyCash), 201);
    }

    return response()->json(['message' => 'Ya haz abierto una caja hoy'], 404);

  }

  /**
   * Display the specified resource.
   */
  public function show(DailyCash $dailyCash)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(DailyCash $dailyCash)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, DailyCash $dailyCash)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(DailyCash $dailyCash)
  {
    //
  }

  public function changeStatus(DailyCashUpdateRequest $request, DailyCash $dailyCash)
  {
    $dailyCash->update($request->all());
    return response()->json(new DailyCashResource($dailyCash), 201);
  }

  public function getLastDailyCashes()
  {
    $dailyCashes = DailyCash::select(['id', 'created_at', 'final_money'])
      ->latest()->limit(5)->get();
    return response()->json($dailyCashes);
  }

  public function getProfit()
  {
    $dailyCash = $this->dailyCashService->searchDailyCashCurrent();
    $profit = $dailyCash->profit ?? -1; //-1 -> Caja no abierta
    return response()->json($profit);
  }

  public function exportData(Request $request)
  {
    $dailyCashes = DailyCash::query()
      ->filterByDate($request)
      ->when($request->filled('minimum_amount'), function ($query) use ($request) {
        $min_amount = $request->minimum_amount;
        $query->where('final_money', '>=', $min_amount);
      })
      ->when($request->filled('maximum_amount'), function ($query) use ($request) {
        $max_amount = $request->maximum_amount;
        $query->where('final_money', '<=', $max_amount);
      })
      ->select(['start_money', 'final_money', 'profit', 'state', 'user_id', 'created_at'])
      ->latest()
      ->get();
    if ($request->type === "excel") {
      return (new DailyCashExport($dailyCashes))->download('dailyCashes.xlsx', Excel::XLSX, ['Content-Type' => 'text/xlsx']);
    } else if ($request->type === "pdf") {
      $pdf = PDF::loadView('exports.dailyCash', ['dailyCashes' => $dailyCashes]);
      return $pdf->download('dailyCash.pdf');
    }
  }
}
