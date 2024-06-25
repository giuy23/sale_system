<?php

namespace App\Http\Services;

use App\Http\Services;
use App\Models\DailyCash;
use Carbon\Carbon;

class DailyCashService
{

  public function increaseCashAmountCurrent($amount)
  {
    $dailyCashCurrent = $this->searchDailyCashCurrent();
    $finalMoney = $dailyCashCurrent->final_money + floatval($amount);
    $this->updateDailyCash($finalMoney, $dailyCashCurrent);
  }
  public function increaseCashAmountByInstance($amount, $dailyCash)
  {
    $finalMoney = $dailyCash->final_money + floatval($amount);
    $this->updateDailyCash($finalMoney, $dailyCash);
  }

  public function decreaseCashAmountCurrent($amount)
  {
    $dailyCashCurrent = $this->searchDailyCashCurrent();
    $finalMoney = $dailyCashCurrent->final_money - floatval($amount);
    $this->updateDailyCash($finalMoney, $dailyCashCurrent);
  }

  public function decreaseCashAmountByInstance($amount, $dailyCash)
  {
    $finalMoney = $dailyCash->final_money - floatval($amount);
    $this->updateDailyCash($finalMoney, $dailyCash);
  }

  public function searchDailyCashCurrent()
  {
    $currentDate = Carbon::today();
    $dailyCash = DailyCash::whereDate('created_at', $currentDate)->where('state', 1)->first();
    return $dailyCash;
  }
  public function searchDailyCashById($id)
  {
    $dailyCash = DailyCash::find($id);
    return $dailyCash;
  }

  private function updateDailyCash($finalAmount, $dailyCash)
  {
    $profit = $finalAmount - $dailyCash->start_money;
    $dailyCash->update(['final_money' => $finalAmount, 'profit' => $profit]);
  }
}
