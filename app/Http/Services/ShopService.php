<?php

namespace App\Http\Services;

use App\Models\CreditSale;
use App\Models\Enterprise;
use App\Models\PaymentHistory;
use App\Models\Product;
use App\Models\ProductSale;
use App\Models\Sale;
use Barryvdh\DomPDF\Facade\PDF;
use Dompdf\Dompdf;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\TryCatch;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

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

      if (!$productPrice) {
        return ['error' => 'Producto no encontrado'];
      }

      if ($productPrice->quantity < $product['quantitySell']) {
        return ['error' => 'Cantidad del producto insuficiente para ' . $productPrice->name];
      }

      $product['sale_price'] = $productPrice->sale_price;
      $product['original_quantity'] = $productPrice->quantity;
      $product['name'] = $productPrice->name;
      $product['total'] = number_format(($productPrice->sale_price - floatval($product['discount'])) * $product['quantitySell'], 2, '.', '');
      $product['discount_total'] = number_format(floatval($product['discount']) * $product['quantitySell'], 2, '.', '');

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
      // Convertir los totales a números flotantes quitando las comas
      $totalPrice += floatval(str_replace(',', '', $product['total']));
      $totalDiscount += floatval(str_replace(',', '', $product['discount_total']));
    }

    $productFinal['total'] = number_format($totalPrice, 2, '.', '');
    $productFinal['igv'] = number_format($totalPrice * 0.18, 2, '.', '');
    $productFinal['sub_total'] = number_format($totalPrice - ($totalPrice * 0.18), 2, '.', ''); // IGV -> 18%
    $productFinal['discount_total'] = number_format($totalDiscount, 2, '.', '');

    return $productFinal;
  }

  public function saveShopInTableSale($sale, $typeSale, $clientId)
  {
    try {
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
    } catch (\Exception $e) {
      return response()->json(['message' => 'Error al crear la venta.'], 500);
    }

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
    } catch (\Exception $e) {
      return response()->json(['message' => 'Error al guardar el detalle de la venta.'], 500);
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
        PaymentHistory::create([
          'credit_sale_id' => $creditSale->id,
          'payment_amount' => $importedCash,
        ]);
      }
    } catch (\Exception $e) {
      return response()->json(['message' => 'Error al crear la venta.'], 500);
    }
  }

  public function discountQuantityProducts($products)
  {
    try {
      foreach ($products as $product) {
        Product::where('id', $product['id'])
          ->update([
            'quantity' => DB::raw('quantity - ' . $product['quantitySell'])
          ]);
      }
    } catch (\Exception $th) {
      return response()->json(['message' => 'Error al crear la venta.'], 500);
    }
  }

  public function createTicket($sale)
  {
    $sales = ProductSale::where('sale_id', $sale->id)->get();
    $enterprise = Enterprise::first();
    $qrCode = $this->createQR($sale, $enterprise);

    $html = view(
      'pdf.detail-sale',
      ['sales' => $sales, 'enterprise' => $enterprise, 'sale' => $sale, 'qrCode' => $qrCode]
    )->render();

    $pdf = new Dompdf();
    $options = $pdf->getOptions();
    $pdf->setPaper('b7', 'portrait');

    $options->set(
      array(
        'isRemoteEnabled' => true,
        'isHtml5ParserEnabled' => true
      )
    );
    $pdf->setOptions($options);
    $pdf->loadHtml($html);

    $GLOBALS['bodyHeight'] = 0;

    $pdf->setCallbacks([
      'myCallbacks' => [
        'event' => 'end_frame',
        'f' => function ($frame) {
          $node = $frame->get_node();

          if (strtolower($node->nodeName) === "body") {
            $padding_box = $frame->get_padding_box();
            $GLOBALS['bodyHeight'] += $padding_box['h'];
          }
        }
      ]
    ]);

    $pdf->render();
    unset($pdf);
    $docHeight = $GLOBALS['bodyHeight'] + 100;

    $pdf = new Dompdf($options);
    $options = $pdf->getOptions();
    $pdf->setPaper([0, 0, 500, $docHeight], 'portrait');
    $pdf->loadHtml($html);
    $pdf->render();
    return $pdf->stream('detail-sale.pdf', ['Attachment' => 0]);

    //SI LA TICKETERA CORTA EXACTO USAR ESTO
    // $pdf = \App::make('dompdf.wrapper');
    // return view('pdf.detail-sale', compact('sales', 'enterprise'));
  }

  private function createQR($sale, $enterprise)
  {
    $data = [
      $enterprise->ruc ?? 00000000000,
      03,//TIPO DE COMPROBANTE -> BOLETA
      env("RUC_SERIE"),
      str_pad($sale->id, 7, '0', STR_PAD_LEFT),
      $sale->total,
      $sale->created_at->format('Y-m-d'),
      01,
      $sale->client_document_number,
    ];

    $qrString = implode('|', $data);
    $result = Builder::create()
      ->writer(new PngWriter())
      ->data($qrString)
      ->size(200)
      ->margin(10)
      ->build();

    $qrCode = base64_encode($result->getString());

    return $qrCode;
  }
}
