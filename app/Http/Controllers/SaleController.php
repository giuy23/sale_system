<?php

namespace App\Http\Controllers;

use App\Http\Services\CancelSale;
use App\Http\Resources\SaleDetailResource;
use App\Http\Resources\SaleResource;
use App\Http\Services\DailyCashService;
use App\Models\Client;
use App\Models\ProductSale;
use App\Models\Sale;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SaleController extends Controller
{
  public function __construct(
    private CancelSale $cancelSale,
    private DailyCashService $dailyCashService,
  ) {
  }
  /**
   * Display a listing of the resource.
   */
  public function index(Request $request)
  {
    $sales = Sale::latest()->paginate(15);

    if ($request->wantsJson()) {
      return SaleResource::collection($sales)->response();
    }
    return Inertia::render('views/SaleView', [
      'sales' => SaleResource::collection($sales),
    ]);
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
  public function show(Sale $sale)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(Sale $sale)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, Sale $sale)
  {
    $request = $request->validate([
      'state' => ['required', 'integer']
    ]);

    $sale->update(['state' => $request['state']]);
    $saleUpdated = Sale::find($sale);

    return response()->json($saleUpdated, 200);
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Sale $sale)
  {
    //
  }



  public function getSaleDetail(Sale $sale)
  {
    $dailyCashCurrent = $this->dailyCashService->searchDailyCashCurrent();
    if (!$dailyCashCurrent) {
      return redirect()->route('dailyCash.index');
    } else {
      $sales = ProductSale::where('sale_id', $sale->id)->get();
      return Inertia::render('views/CancelSaleView', [
        'saleDetails' => SaleDetailResource::collection($sales),
      ]);
    }
  }

  public function confirmCancelSale(Request $request)
  {
    $saleId = $request['saleId'];
    $products = $request['products'];
    $clientId = Sale::where('id', $saleId)->value('client_id');

    $enoughMoneyInBox = $this->cancelSale->verifyMoneyEnoughInCurrentBox($products);
    if (!$enoughMoneyInBox) {
      return response()->json(['message' => 'No tienes dinero suficiente en la caja, genere más ventas o cree un ingreso'], 404);
    }
    $correctDevolution = $this->cancelSale->returnProductsToSale($products);
    if ($correctDevolution) {
      $correctTransaction = $this->cancelSale->verifyNewSale($products, $clientId);
      if ($correctTransaction !== true) {
        return $correctTransaction; // This will return the JSON response from verifyNewSale
      }
      $this->cancelSale($saleId);
      $this->cancelSale->updateDailyCash($saleId, $products);
      return response()->json(['message' => 'Venta cancelada correctamente.'], 200);
    } else {
      return response()->json(['message' => 'Error al restaurar stock de productos.'], 404);
    }
  }

  private function cancelSale($saleId)
  {
    $sale = Sale::find($saleId);
    $sale->update(['state' => 2]);

    return response()->json([], 200);
  }

  public function getAmountTotalSalesToday()
  {
    $currentDate = Carbon::today();
    $sales = Sale::select('total')->whereDate('created_at', $currentDate)->where('state', 1)->get();

    $totalSales = number_format($sales->sum('total') ?? 0, 1);
    // dd($totalSales);
    return response()->json($totalSales);
  }

  // public function getDetailSalePdf(Sale $sale)
  // {
  //   $sales = ProductSale::where('sale_id', $sale->id)->get();
  //   $pdf = Pdf::loadView('pdf.detail-sale', [
  //     'sales' => $sales,
  //   ])->setPaper('b7', 'portrait');
  //   $pdf->stream('pdf.detail-sale.pdf');
  // }
  // public function getDetailSalePdf(Sale $sale)
  // {
  //   $sales = ProductSale::where('sale_id', $sale->id)->get();
  //   // $pdf = Pdf::loadView('pdf.detail-sale', [
  //   //   'sales' => $sales,
  //   // ])->setPaper([0, 0, 80, 1000], 'portrait'); // Ancho fijo, largo dinámico

  //   // return $pdf->stream('detail-sale.pdf');
  //   $html = 'pdf.detail-sale.pdf';
  //   $dompdf = Pdf::loadView('pdf.detail-sale', [
  //     'sales' => $sales,
  //   ]);
  //   // $dompdf = new Dompdf();
  //   $dompdf->set_paper(array(0, 0, 600, 800));

  //   $GLOBALS['bodyHeight'] = 0;

  //   $dompdf->setCallbacks(
  //     array(
  //       'myCallbacks' => array(
  //         'event' => 'end_frame',
  //         'f' => function ($frame) {
  //           $node = $frame->get_node();

  //           if (strtolower($node->nodeName) === "body") {
  //             $padding_box = $frame->get_padding_box();
  //             $GLOBALS['bodyHeight'] += $padding_box['h'];
  //           }
  //         }
  //       )
  //     )
  //   );

  //   $dompdf->loadHtml($html);
  //   $dompdf->render();
  //   unset($dompdf);
  //   dd($GLOBALS['bodyHeight']);
  //   $dompdf = new Dompdf();
  //   $dompdf->set_paper(array(0, 0, 600, $GLOBALS['bodyHeight'] + 50));
  //   // $dompdf->loadHtml($html);
  //   // $dompdf->render();
  //   $dompdf->stream($html, [
  //     'sales' => $sales,
  //   ]);

  //   // dd($docHeight);
  // }

  public function getDetailSalePdf(Sale $sale)
  {
    $sales = ProductSale::where('sale_id', $sale->id)->get();

    $html = view('pdf.detail-sale', ['sales' => $sales])->render();

    $pdf = new Dompdf();
    $options = $pdf->getOptions();
    $pdf->setPaper('b7', 'portrait'); // Ancho fijo, altura temporal

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
    // return $pdf->stream('detail-sale.pdf', ['sales' => $sales]);
  }
}
