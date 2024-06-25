<?php

namespace App\Http\Controllers;

use App\Http\Services\CancelSale;
use App\Http\Resources\SaleDetailResource;
use App\Http\Resources\SaleResource;
use App\Http\Services\DailyCashService;
use App\Models\Client;
use App\Models\ProductSale;
use App\Models\Sale;
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
}
