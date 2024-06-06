<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProviderRequest;
use App\Http\Resources\ProviderResource;
use App\Models\Provider;
use App\Traits\ImageTrait;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProviderController extends Controller
{
  use ImageTrait;

  public function index(Request $request)
  {
    $providers = Provider::query()->filterData($request)->paginate(15);
    if ($request->wantsJson()) {
      return ProviderResource::collection($providers)->response();
    }
    return Inertia::render('views/ProviderView', [
      'providers' => ProviderResource::collection($providers)
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
  public function store(ProviderRequest $request)
  {
    $provider = Provider::create($request->all());
    $urlImage = $this->uploadImage($request->image, $provider, 'images/providers', false);
    $provider->image = $urlImage;

    return response()->json(new ProviderResource($provider), 201);
  }

  /**
   * Display the specified resource.
   */
  public function show(Provider $provider)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(Provider $provider)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(ProviderRequest $request, Provider $provider)
  {
    $provider->update($request->all());
    if ($request->hasFile('image')) {
      $urlImage = $this->editImage($request->image, $provider, 'images/providers');
      $provider->image = $urlImage;
    }

    return response()->json(new ProviderResource($provider));
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Provider $provider)
  {
    $this->deleteImage($provider, 'images/providers');
    $provider->delete();

    return response()->json(['success' => true]);
  }
}
