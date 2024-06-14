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

  public function destroy(Client $client)
  {
    $idClient = $client->id;
    if ($idClient === 1) {
      return response()->json([], 403);
    }

    $client->delete();

    return response()->json(['success' => true]);
  }
}
