<?php

namespace App\Http\Controllers;

use App\Http\Resources\SaleDetailResource;
use App\Http\Services\DailyCashService;
use App\Models\ProductSale;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SaleDetailController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function __construct(private DailyCashService $dailyCashService)
  {
  }
  public function index(Request $request)
  {
    $dailyCashCurrent = $this->dailyCashService->searchDailyCashCurrent();
    if (!$dailyCashCurrent) {
      return redirect()->route('dailyCash.index');
    } else {
      $sales = ProductSale::where('sale_id', $request->id)->get();
      return Inertia::render('views/CancelSaleView', [
        'saleDetails' => SaleDetailResource::collection($sales),
      ]);
    }
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
  public function store(Request $request)
  {
    //
  }

  /**
   * Display the specified resource.
   */
  public function show(ProductSale $productSale)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(ProductSale $productSale)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, ProductSale $productSale)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(ProductSale $productSale)
  {
    //
  }
}
