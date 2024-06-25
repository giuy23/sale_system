<?php

namespace App\Http\Services;

use App\Models\DailyCash;
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
  }

  public function verifyMoneyEnoughInCurrentBox($products)
  {
    $total = $this->calculateTotalAmount($products);
    $cashIsOpen = $this->dailyCashService->searchDailyCashCurrent();
    if ($cashIsOpen->final_money < $total) {
      return false;
    }
    return true;
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
        if ($sale) {
          $success = $this->saveShopInTableSaleDetail($productsToSale, $sale['id']);
          if ($success) {
            $this->dailyCashService->increaseCashAmountCurrent($totalAmount['total']);
            $this->shopService->discountQuantityProducts($productsToSale);
            return response()->json(['message' => 'Venta creada con éxito.'], 200);
          } else {
            return response()->json(['message' => 'Error al guardar el detalle de la venta.'], 404);
          }
        } else {
          return response()->json(['message' => 'Error al crear la venta.'], 404);
        }
      }
      return true;
    } catch (\Exception $e) {
      return response()->json(['message' => 'Error al procesar la venta.'], 500);
    }
  }

  public function updateDailyCash($saleId, $products)
  {
    $total = $this->calculateTotalAmount($products);
    $sale = Sale::find($saleId);
    $dailyCash = DailyCash::whereDate('created_at', $sale->created_at)->first();

    $this->dailyCashService->decreaseCashAmountByInstance($total, $dailyCash);
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

  private function calculateTotalAmount($products)
  {
    $total = 0;
    foreach ($products as $product) {
      $total += $product['total'];
    }
    return $total;
  }
}
