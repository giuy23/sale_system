<?php

namespace App\Http\Controllers;

use App\Exports\SaleExport;
use App\Http\Services\CancelSale;
use App\Http\Resources\SaleDetailResource;
use App\Http\Resources\SaleResource;
use App\Http\Services\DailyCashService;
use App\Http\Services\ShopService;
use App\Models\Client;
use App\Models\Enterprise;
use App\Models\ProductSale;
use App\Models\Sale;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Maatwebsite\Excel\Excel;

class SaleController extends Controller
{
  public function __construct(
    private CancelSale $cancelSale,
    private DailyCashService $dailyCashService,
    private ShopService $shopService,
  ) {
  }
  /**
   * Display a listing of the resource.
   */
  public function index(Request $request)
  {
    $sales = Sale::query()
      ->filterByDate($request)
      ->when($request->filled('minimum_amount'), function ($query) use ($request) {
        $min_amount = $request->minimum_amount;
        $query->where('total', '>=', $min_amount);
      })
      ->when($request->filled('maximum_amount'), function ($query) use ($request) {
        $max_amount = $request->maximum_amount;
        $query->where('total', '<=', $max_amount);
      })
      ->latest()
      ->paginate(15);

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

  public function confirmCancelSale(Request $request)
  {
    $saleId = $request['saleId'];
    $products = $request['products'];
    $clientId = Sale::where('id', $saleId)->value('client_id');

    $amountReturned = $this->cancelSale->verifyMoneyEnoughInCurrentBox($products);
    if (!$amountReturned) {
      return response()->json(['message' => 'No tienes dinero suficiente en la caja, genere más ventas o cree un ingreso'], 404);
    }
    DB::beginTransaction();
    try {
      $correctDevolution = $this->cancelSale->returnProductsToSale($products);
      if ($correctDevolution) {
        $this->cancelSale->verifyNewSale($products, $clientId);

        $this->cancelSale($saleId);
        $sale = $this->cancelSale->updateDailyCash($saleId);
        $this->cancelSale->decreaseAmountCurrentCash($amountReturned, $sale);
        DB::commit();
        return response()->json(['message' => 'Venta cancelada correctamente.'], 200);
      }
    } catch (\Exception $e) {
      DB::rollBack();
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
    return response()->json($totalSales);
  }

  public function getDetailSalePdf(Sale $sale)
  {
    $this->shopService->createTicket($sale);
  }

  public function exportData(Request $request)
  {
    $sales = Sale::query()
      ->filterByDate($request)
      ->when($request->filled('minimum_amount'), function ($query) use ($request) {
        $min_amount = $request->minimum_amount;
        $query->where('total', '>=', $min_amount);
      })
      ->when($request->filled('maximum_amount'), function ($query) use ($request) {
        $max_amount = $request->maximum_amount;
        $query->where('total', '<=', $max_amount);
      })
      ->select([
        'sub_total',
        'discount_total',
        'total',
        'igv',
        'state',
        'client_id',
        'created_at',
      ])
      ->latest()
      ->get();

    if ($request->type === "excel") {
      return (new SaleExport($sales))->download('invoices.xlsx', Excel::XLSX, ['Content-Type' => 'text/xlsx']);
    } else if ($request->type === "pdf") {
      $pdf = PDF::loadView('exports.sales', ['sales' => $sales]);
      return $pdf->download('invoices.pdf');
    }
  }

}
