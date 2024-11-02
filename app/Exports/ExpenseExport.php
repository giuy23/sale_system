<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ExpenseExport implements FromView, ShouldAutoSize
{
  use Exportable;
  /**
   * @return \Illuminate\Support\Collection
   */
  private $expenses;
  public function __construct($expenses)
  {
    $this->expenses = $expenses;
  }
  public function view(): View
  {
    return view('exports.expenses', [
      'expenses' => $this->expenses
    ]);
  }
}
