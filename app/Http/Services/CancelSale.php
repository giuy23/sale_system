<?php

namespace App\Http\Services;

use App\Models\DailyCash;
use App\Models\Expense;
use App\Models\Product;
use App\Models\ProductSale;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\isEmpty;

class CancelSale
{

  public function __construct(
    private ShopService $shopService,
    private DailyCashService $dailyCashService,
  ) {
    $this->dailyCashCurrent = $this->dailyCashService->searchDailyCashCurrent();
  }


  public $dailyCashCurrent;

  public function verifyMoneyEnoughInCurrentBox($products)
  {
    $total = $this->calculateAmountToReturned($products);

    if ($this->dailyCashCurrent->final_money < $total) {
      return false;
    }
    return $total;
  }

  public function returnProductsToSale($products)
  {
    try {
      DB::beginTransaction();
      foreach ($products as $product) {
        $productFound = Product::find($product['product_id']);
        if ($productFound !== null) {
          $productFound->quantity += $product['quantity'];
          $productFound->save();
        } else {
          DB::rollBack();
          return false;
        }
      }
      DB::commit();
      return true;
    } catch (\Exception $e) {
      DB::rollBack();
      return false;
    }
  }

  public function verifyNewSale($products, $clientId)
  {
    DB::beginTransaction();
    try {
      $productsToSale = [];
      foreach ($products as $product) {
        if ($product['quantitySell'] > 0) {
          $product['discount_total'] = number_format($product['discount'] * $product['quantitySell'], 2);
          $product['total'] = number_format(($product['price'] - $product['discount']) * $product['quantitySell'], 2);
          $productsToSale[] = $product;
        }
      }
      if (!empty($productsToSale)) {
        $totalAmount = $this->shopService->calculateTotalAmountSale($productsToSale);
        $sale = $this->shopService->saveShopInTableSale($totalAmount, 1, $clientId);
        $success = $this->saveShopInTableSaleDetail($productsToSale, $sale['id']);
        if ($success) {
          $this->dailyCashService->increaseCashAmountCurrent($totalAmount['total']);
          $this->shopService->discountQuantityProducts($productsToSale);

          DB::commit();
          return response()->json(['message' => 'Venta creada con éxito.'], 200);
        } else {
          return response()->json(['message' => 'Error al guardar el detalle de la venta.'], 404);
        }
      }
      DB::commit();
      return true;
    } catch (\Exception $e) {
      DB::rollBack();
      return response()->json(['message' => 'Error al procesar la venta.'], 500);
    }
  }

  public function updateDailyCash($saleId)
  {
    $sale = Sale::find($saleId);
    // $dailyCash = DailyCash::whereDate('created_at', $sale->created_at)->first();
    // $this->dailyCashService->decreaseCashAmountByInstance($sale->total, $dailyCash);
    return $sale;
  }

  public function decreaseAmountCurrentCash($amount, $sale)
  {
    Expense::create([
      'amount' => $amount,
      'description' => 'Monto devuelvo de la venta N° ' . $sale->id,
      'type' => 2,
      'daily_cash_id' => $this->dailyCashCurrent->id,
    ]);
    $this->dailyCashService->decreaseCashAmountCurrent($sale->total);
  }

  private function saveShopInTableSaleDetail($products, $saleId)
  {
    try {
      foreach ($products as $product) {
        ProductSale::create([
          'sale_id' => $saleId,
          'product_id' => $product['product_id'],
          'quantity' => $product['quantitySell'],
          'discount' => $product['discount'],
          'price' => $product['price'],
          'total' => $product['total'],
        ]);
      }
      return true;
    } catch (\Exception $e) {
      return false;
    }
  }

  private function calculateAmountToReturned($products)
  {
    $total = 0;
    foreach ($products as $product) {
      $quantity = $product['quantity'] - $product['quantitySell'];
      $total += ($product['price'] - $product['discount']) * $quantity;
    }
    return floatval($total);
  }
}
