<?php

namespace App\Http\Controllers;

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
    $dailyCashes = DailyCash::query()->filterData($request)
      ->latest()->paginate(15);

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
    // dd($dailyCash);
    $profit = $dailyCash->profit ?? -1; //-1 -> Caja no abierta
    // if ($profit == null) {
    //   $profit = -1;
    // }
    // dd($profit, $profit == -1);

    return response()->json($profit);
  }
}
