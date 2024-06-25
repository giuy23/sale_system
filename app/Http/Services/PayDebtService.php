<?php

namespace App\Http\Services;

use App\Http\Resources\CreditSaleResource;
use App\Models\CreditSale;
use App\Models\Expense;
use App\Models\PaymentHistory;
use App\Models\Sale;

class PayDebtService
{

  public function __construct(private DailyCashService $dailyCashService)
  {
    $this->dailycashCurrent = $this->dailyCashService->searchDailyCashCurrent();
  }
  public $dailycashCurrent;
  public function createPaymentHistory($creditSaleId, $amount)
  {
    PaymentHistory::create([
      'credit_sale_id' => $creditSaleId,
      'payment_amount' => $amount,
    ]);
  }

  public function updateAmountRemaining($creditSale, $amount)
  {
    $isPaid = false;
    $newAmount = number_format($creditSale->remaining_amount - $amount, 2);
    if ($newAmount == 0) {
      $isPaid = true;
    }
    $creditSale->update(['remaining_amount' => $newAmount, 'is_paid' => $isPaid]);
    $this->createEntryCurrentCash($amount, $creditSale);
    if ($isPaid) {
      $this->changeStateInTableSale($creditSale->sale_id);
    }
  }

  public function cancelAllDebts($creditSaleIds)
  {
    try {
      foreach ($creditSaleIds as $creditSaleId) {
        $saleCredit = $this->searchCreditSale($creditSaleId);
        $this->createPaymentHistory($saleCredit->id, $saleCredit->remaining_amount);
        $this->updateAmountRemaining($saleCredit, $saleCredit->remaining_amount);
      }
    } catch (\Exception $e) {
      return response()->json([], 500);//CACHEAR EL ERROR EN BOOTSTRAP
    }
  }

  private function changeStateInTableSale($creditSaleId)
  {
    $sale = Sale::where('id', $creditSaleId)->first();
    $sale->update(['state' => 4]);
  }

  private function searchCreditSale($id)
  {
    $saleCredit = CreditSale::where('id', $id)->first();
    return $saleCredit;
  }

  private function createEntryCurrentCash($amount, $creditSale)
  {
    Expense::create([
      'amount' => $amount,
      'description' => 'Monto Pagado de la venta N°' . $creditSale->id,
      'type' => 3,
      'daily_cash_id' => $this->dailycashCurrent->id,
    ]);
    $this->dailyCashService->increaseCashAmountCurrent($amount);
  }
}
