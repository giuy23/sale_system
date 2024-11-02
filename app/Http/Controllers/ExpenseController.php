<?php

namespace App\Http\Controllers;

use App\Exports\ExpenseExport;
use App\Http\Requests\ExpenseRequest;
use App\Http\Resources\ExpenseResource;
use App\Http\Services\DailyCashService;
use App\Models\Expense;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Maatwebsite\Excel\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class ExpenseController extends Controller
{
  public function __construct(
    private DailyCashService $dailyCashService,
  ) {
  }
  /**
   * Display a listing of the resource.
   */
  public function index(Request $request)
  {
    $expenses = Expense::where('daily_cash_id', $request['id'])->paginate(15);
    // dd($expenses, $request->id);
    return Inertia::render('views/ExpenseView', [
      'expenses' => ExpenseResource::collection($expenses),
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
  public function store(ExpenseRequest $request)
  {
    $dailyCash = $this->dailyCashService->searchDailyCashById($request['daily_cash_id']);
    $amountExpense = $request['amount'];
    if ($request['type'] == 2) {
      if ($dailyCash->final_money < $amountExpense) {
        return response()->json(['message' => 'El monto de egreso es mayor al monto de la caja'], 404);
      } else {
        $this->dailyCashService->decreaseCashAmountByInstance($amountExpense, $dailyCash);
      }
    } else {
      $this->dailyCashService->increaseCashAmountByInstance($amountExpense, $dailyCash);
    }
    $expense = Expense::create($request->all());

    return response()->json(new ExpenseResource($expense), 201);
  }

  /**
   * Display the specified resource.
   */
  public function show(Expense $expense)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(Expense $expense)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(ExpenseRequest $request, Expense $expense)
  {
    $expense->update($request->all());

    return response()->json(new ExpenseResource($expense), 201);
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Expense $expense)
  {
    //
  }

  public function getBalance()
  {
    $currentDate = Carbon::today();
    $exits = Expense::whereDate('created_at', $currentDate)->where('type', 2)->get();
    $entries = Expense::whereDate('created_at', $currentDate)->whereIn('type', [1, 3])->get();

    $entries = number_format($entries->sum('amount') ?? 0, 2);
    $exits = number_format($exits->sum('amount') ?? 0, 2);
    return response()->json(['entries' => $entries, 'exits' => $exits]);
  }

  public function exportData(Request $request)
  {
    $expenses = Expense::query()
      ->filterByDate($request)
      ->select([
        'amount',
        'description',
        'type',
        'created_at',
      ])
      ->latest()
      ->get();

    if ($request->type === "excel") {
      return (new ExpenseExport($expenses))->download('invoices.xlsx', Excel::XLSX, ['Content-Type' => 'text/xlsx']);
    } else if ($request->type === "pdf") {
      $pdf = PDF::loadView('exports.expenses', ['expenses' => $expenses]);
      return $pdf->download('invoices.pdf');
    }
  }
}
