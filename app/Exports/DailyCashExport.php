<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class DailyCashExport implements FromView, ShouldAutoSize
{
  use Exportable;

  private $dailyCashes;

  public function __construct($dailyCashes)
  {
    $this->dailyCashes = $dailyCashes;
  }
  /**
   * @return \Illuminate\Support\Collection
   */
  public function view(): View
  {
    return view('exports.dailyCash', [
      'dailyCashes' => $this->dailyCashes
    ]);
  }
}
