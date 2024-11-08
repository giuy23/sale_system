<?php

namespace App\Http\Controllers;

use App\Http\Services\DailyCashService;
use App\Http\Services\ShopService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Barryvdh\DomPDF\Facade\Pdf;

class ShopController extends Controller
{
  public function __construct(
    private ShopService $shopService,
    private DailyCashService $dailyCashService,
  ) {
  }
  public function index()
  {
    $dailyCashCurrent = $this->dailyCashService->searchDailyCashCurrent();
    if (!$dailyCashCurrent) {
      return redirect()->route('dailyCash.index');
    } else {
      return Inertia::render('views/ShopView');
    }
  }

  public function createSale(Request $request)
  {
    $saleType = $request['saleType'];
    $products = $request['products'];

    DB::beginTransaction();
    try {
      $productsWithPrice = $this->shopService->getProductsToSale($products);
      if (isset($productsWithPrice['error'])) {
        return response()->json(['message' => $productsWithPrice['error']], 404);
      }
      $totalAmount = $this->shopService->calculateTotalAmountSale($productsWithPrice);
      $sale = $this->shopService->saveShopInTableSale($totalAmount, $saleType, $request->client_id);
      $this->shopService->saveShopInTableSaleDetail($productsWithPrice, $sale->id);
      if ($saleType === 3) {
        $this->shopService->saveShopInCreditSale($totalAmount, $sale->id, $request->customerPayment, $request->description);
      } else {
        $this->dailyCashService->increaseCashAmountCurrent($totalAmount['total']);
      }

      $this->shopService->discountQuantityProducts($productsWithPrice);
      DB::commit();

      return response()->json(['message' => 'Venta creada con éxito.', 'sale_id' => $sale->id], 200);
      // return response()->json(['message' => 'Venta creada con éxito.'], 200);
    } catch (\Exception $e) {
      DB::rollback();
      return response()->json(['message' => 'Error al crear la venta.'], 500);
    }
  }
}
