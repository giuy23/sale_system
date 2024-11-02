<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClientRequest;
use App\Http\Resources\ClientResource;
use App\Models\Client;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ClientController extends Controller
{
  public function index(Request $request)
  {
    $clients = Client::query()
      ->filterData($request)
      ->when($request->filled('search'), function ($query) use ($request) {
        $search = $request->search;
        $query->orWhere('document_number', 'like', '%' . $search . '%');
        $query->orWhere('full_name', 'like', '%' . $search . '%');
      })->paginate(15);

    if ($request->wantsJson()) {
      return ClientResource::collection($clients)->response();
    }
    return Inertia::render('views/ClientView', [
      'clients' => ClientResource::collection($clients),
    ]);
  }

  public function store(ClientRequest $request)
  {
    $client = Client::create($request->all());
    $client['state'] = 1;

    return response()->json(new ClientResource($client), 201);
  }

  public function update(ClientRequest $request, Client $client)
  {
    $idClient = $client->id;
    if ($idClient === 1) {
      return response()->json([], 403);
    }

    $client->update($request->all());

    return response()->json(new ClientResource($client), 201);
  }

  public function changeState(Request $request, Client $client)
  {
    $request->validate(['state' => ['required', 'boolean']]);
    $client->update(['state' => $request->state]);
    $updateClient = Client::find($client->id);

    return response()->json(new ClientResource($updateClient));
  }

  public function destroy(Client $client)
  {
    $debts = $client->debts()->where('is_paid', 0)->count();

    if ($debts > 0) {
      return response()->json(['message' => 'El usuario tiene deudas pendientes, no se puede eliminar'], 404);
    }

    if ($client->id === 1) {
      return response()->json(['message' => 'No tienes permiso para eliminar este cliente'], 403);
    }

    $client->delete();
    return response()->json(['success' => true]);
  }

  public function clientsToSell(Request $request)
  {
    $clients = Client::select(['id', 'full_name', 'document_number'])->where('state', 1)
      ->when($request->filled('value'), function ($query) use ($request) {
        $search = $request['value'];
        $query->where('full_name', 'like', '%' . $search . '%')
          ->orwhere('document_number', 'like', '%' . $search . '%');
      })
      ->get();

    return response()->json($clients, 200);
  }
}
