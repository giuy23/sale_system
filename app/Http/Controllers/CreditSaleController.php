<?php

namespace App\Http\Controllers;

use App\Http\Resources\ClientSaleCreditResource;
use App\Http\Resources\CreditSaleResource;
use App\Http\Services\DailyCashService;
use App\Http\Services\PayDebtService;
use App\Models\Client;
use App\Models\CreditSale;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class CreditSaleController extends Controller
{
  public function __construct(
    private PayDebtService $payDebtService,
    private DailyCashService $dailyCashService,
  ) {
  }
  /**
   * Display a listing of the resource.
   */
  public function index(Request $request)
  {
    $saleCredits = Client::select(['id', 'full_name', 'document_number'])
      ->whereHas('sales', function ($query) {
        $query->where('state', 3);
      })
      ->when($request->filled('search'), function ($query) use ($request) {
        $search = $request->input('search');
        $query->where(function ($q) use ($search) {
          $q->where('full_name', 'like', '%' . $search . '%')
            ->orWhere('document_number', 'like', '%' . $search . '%');
        });
      })
      ->paginate(15);

    // $saleCredits = Client::select(['id', 'full_name', 'document_number'])->whereHas('sales', function ($query) {
    //   $query->where('state', 3);
    // })->when($request->filled('search'), function ($query) use ($request) {
    //   $search = $request['search'];
    //   $query->where('full_name', 'like', '%' . $search . '%')
    //     ->orWhere('document_number', 'like', '%' . $search . '%');
    // })->paginate(15);

    if ($request->wantsJson()) {
      return ClientSaleCreditResource::collection($saleCredits);
    }

    return Inertia::render(
      'views/SaleCredit/CreditSaleView',
      ['saleCredits' => ClientSaleCreditResource::collection($saleCredits)]
    );
  }

  public function getDebtsFromClient(Request $request)
  {
    $saleCredits = CreditSale::whereHas('sale', function ($query) use ($request) {
      $query->where('client_id', $request['id'])
        ->where('state', 3);
    })->paginate(15);

    $client = Client::select(['id', 'full_name'])->find($request['id']);
    $totalDebt = floatval($saleCredits->sum('remaining_amount'));
    $totalDebt = round($totalDebt, 2);

    return Inertia::render('views/SaleCredit/DebtClientDetailView', [
      'saleCredits' => CreditSaleResource::collection($saleCredits),
      'totalDebt' => $totalDebt,
      'client' => $client,
    ]);
  }

  public function payOneDebt(Request $request, CreditSale $creditSale)
  {
    $request = $request->validate(['amount' => ['required', 'numeric', 'min:0']]);
    $amount = $request['amount'];
    $dailyCashCurrent = $this->dailyCashService->searchDailyCashCurrent();

    if (!$dailyCashCurrent) {
      return response()->json(['message' => 'No hay una Caja abierta. Abra una caja'], 404);
    }

    try {
      DB::beginTransaction();

      // Llamar al método para crear el historial de pago
      $this->payDebtService->createPaymentHistory($creditSale->id, $amount);

      // Llamar al método para actualizar el saldo restante
      $this->payDebtService->updateAmountRemaining($creditSale, $amount);

      DB::commit();

      // Devolver una respuesta JSON al cliente
      return response()->json(['message' => 'Pago exitoso'], 200);
    } catch (\Exception $e) {
      DB::rollback();
      // Manejar cualquier error aquí
      return response()->json(['error' => 'Error en el pago: ' . $e->getMessage()], 500);
    }
  }

  public function payAllDebts(Request $request)
  {
    $request = $request->validate([
      'creditSaleIds.*.id' => ['required', 'integer'],
    ]);

    $dailyCashCurrent = $this->dailyCashService->searchDailyCashCurrent();
    if (!$dailyCashCurrent) {
      return response()->json(['message' => 'No hay una Caja abierta. Abra una caja'], 404);
    }

    $creditSaleIds = $request['creditSaleIds'];
    try {
      DB::beginTransaction();
      $this->payDebtService->cancelAllDebts($creditSaleIds);
      DB::commit();
    } catch (\Exception $e) {
      DB::rollback();
      return response()->json(['error' => 'Error en el pago: ' . $e->getMessage()], 500);
    }

    return response()->json([], 200);
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
  public function show(CreditSale $creditSale)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(CreditSale $creditSale)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, CreditSale $creditSale)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(CreditSale $creditSale)
  {
    //
  }
}
