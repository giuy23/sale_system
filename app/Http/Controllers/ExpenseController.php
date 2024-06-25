<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExpenseRequest;
use App\Http\Resources\ExpenseResource;
use App\Http\Services\DailyCashService;
use App\Models\Expense;
use Illuminate\Http\Request;
use Inertia\Inertia;

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
}
