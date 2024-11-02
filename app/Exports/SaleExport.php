<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SaleExport implements FromView, ShouldAutoSize
{
  use Exportable;
  /**
   * @return \Illuminate\Support\Collection
   */
  private $sales;
  public function __construct($sales)
  {
    $this->sales = $sales;
  }
  public function view(): View
  {
    return view('exports.sales', [
      'sales' => $this->sales
    ]);
  }
}
