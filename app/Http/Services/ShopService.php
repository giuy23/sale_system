<?php

namespace App\Http\Services;

use App\Models\CreditSale;
use App\Models\PaymentHistory;
use App\Models\Product;
use App\Models\ProductSale;
use App\Models\Sale;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\TryCatch;

class ShopService
{
  // public function __construct(Type $var = null) {
  //   $this->var = $var;
  // }

  public function getProductsToSale($products)
  {
    $productsWithPrice = [];

    foreach ($products as $product) {
      $productPrice = Product::select(['sale_price', 'quantity', 'name'])
        ->where('id', $product['id'])
        ->first();

      if ($productPrice['quantity'] < $product['quantitySell']) {
        return ['error' => 'Cantidad del producto insuficiente'];
      }

      $product['sale_price'] = $productPrice->sale_price;
      $product['original_quantity'] = $productPrice->quantity;
      $product['name'] = $productPrice->name;
      $product['total'] = number_format(($productPrice->sale_price - $product['discount']) * $product['quantitySell'], 2);
      $product['discount_total'] = number_format($product['discount'] * $product['quantitySell'], 2);
      $productsWithPrice[] = $product;
    }

    return $productsWithPrice;
  }

  public function calculateTotalAmountSale($products)
  {
    $totalPrice = 0;
    $totalDiscount = 0;
    $productFinal = [];

    foreach ($products as $product) {
      $totalPrice += $product['total'];
      $totalDiscount += $product['discount_total'];
    }

    $productFinal['total'] = $totalPrice;
    $productFinal['igv'] = number_format($totalPrice * 0.18, 2, '.', '');
    $productFinal['sub_total'] = number_format(($totalPrice - ($totalPrice * 0.18)), 2, '.', ''); //IGV -> 18%
    $productFinal['discount_total'] = number_format($totalDiscount, 2, '.', '');

    return $productFinal;
  }

  public function saveShopInTableSale($sale, $typeSale, $clientId)
  {
    $sale = Sale::create([
      'sub_total' => $sale['sub_total'],
      'total' => $sale['total'],
      'discount_total' => $sale['discount_total'],
      'igv' => $sale['igv'],
      'state' => $typeSale,
      'user_id' => Auth::id(),
      'client_id' => $clientId,
    ]);

    return $sale;
  }

  public function saveShopInTableSaleDetail($products, $saleId)
  {
    try {
      foreach ($products as $product) {
        ProductSale::create([
          'sale_id' => $saleId,
          'product_id' => $product['id'],
          'quantity' => $product['quantitySell'],
          'discount' => $product['discount'],
          'price' => $product['sale_price'],
          'total' => $product['total'],
        ]);
      }
      return true;
    } catch (\Exception $e) {
      return false;
    }
  }

  public function saveShopInCreditSale($data, $saleId, $paymentAmount, $description)
  {
    $importedCash = number_format($paymentAmount, 2);
    try {
      $creditSale = CreditSale::create([
        'amount_payable' => $data['total'],
        'remaining_amount' => $data['total'] - $importedCash,
        'description' => $description,
        'sale_id' => $saleId,
      ]);

      if ($importedCash) {
        $payment = PaymentHistory::create([
          'credit_sale_id' => $creditSale->id,
          'payment_amount' => $importedCash,
        ]);
      }
      return true;
    } catch (\Exception $e) {
      return false;
    }
  }

  public function discountQuantityProducts($products)
  {
    foreach ($products as $product) {
      Product::where('id', $product['id'])
        ->update([
          'quantity' => DB::raw('quantity - ' . $product['quantitySell'])
        ]);
    }
  }
}
