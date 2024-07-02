<?php

namespace App\Http\Controllers;

use App\Http\Requests\EnterpriseRequest;
use App\Http\Resources\EnterpriseResource;
use App\Models\Enterprise;
use App\Traits\ImageTrait;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EnterpriseController extends Controller
{
  use ImageTrait;
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    $enterprise = Enterprise::firstOrFail();

    return Inertia::render('views/EnterpriseView', [
      'enterprise' => new EnterpriseResource($enterprise),
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
  public function show(Enterprise $enterprise)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(Enterprise $enterprise)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(EnterpriseRequest $request, Enterprise $enterprise)
  {
    $enterprise->update($request->all());
    // if ($request->hasFile('image')) {
    //   $urlImage = $enterprise->editImage($request->image, $enterprise, Enterprise::path);
    //   $enterprise->image = $urlImage;
    // }

    return response()->json(new EnterpriseResource($enterprise), 201);
  }

  public function updateImage(Request $request)
  {
    $request->validate(['image' => ['required', 'image', 'max:4096']]);
    $enterprise = Enterprise::findOrFail($request->enterprise_id);

    if ($request->hasFile('image')) {
      $urlImage = $enterprise->editImage($request->image, $enterprise, Enterprise::path);
      $enterprise->image = $urlImage;
    }

    return response()->json(['success' => true], 201);
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Enterprise $enterprise)
  {
    //
  }
}
