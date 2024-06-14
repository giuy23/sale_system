<?php

namespace App\Http\Controllers;

use App\Http\Services\ShopService;
use App\Models\DailyCash;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ShopController extends Controller
{
  public function __construct(
    private ShopService $shopService,
  ) {
  }
  public function index()
  {
    $currentDate = Carbon::today();
    $cashIsOpen = DailyCash::whereDate('created_at', $currentDate)->first();

    if (!$cashIsOpen) {
      return response()->json(['error' => 'No tienes una caja abierta'], 400);
    }

    return Inertia::render('views/ShopView');
  }

  public function createSale(Request $request)
  {
    $saleType =  $request['saleType'];
    $products = $request['products'];

    $productsWithPrice = $this->shopService->getProductsToSale($products);
    $totalAmount = $this->shopService->calculateTotalAmountSale($productsWithPrice);

    $sale = $this->shopService->saveShopInTableSale($totalAmount, $saleType, $request['client_id']);
    if ($sale) {
      $success = $this->shopService->saveShopInTableSaleDetail($productsWithPrice, $sale['id']);
      if ($success) {
        if ($saleType === 3) {
          $this->shopService->saveShopInCreditSale($totalAmount, $sale['id'], $request['customerPayment'], $request['description']);
        }
        $this->shopService->discpuntQuantityProducts($productsWithPrice);
        return response()->json(['message' => 'Venta creada con éxito.'], 200);
      } else {
        return response()->json(['message' => 'Error al guardar el detalle de la venta.'], 500);
      }
    } else {
      return response()->json(['message' => 'Error al crear la venta.'], 500);
    }
  }
}
