<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Traits\ImageTrait;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductController extends Controller
{
  use ImageTrait;
  public function index(Request $request)
  {
    $products = Product::query()->filterData($request)->paginate(15);

    if ($request->wantsJson()) {
      return ProductResource::collection($products)->response();
    }

    return Inertia::render('views/ProductView', [
      'products' => ProductResource::collection($products),
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
  public function store(ProductRequest $request)
  {
    $product = Product::create($request->all());
    $urlImage = $this->uploadImage($request->image, $product, Product::path, true);
    $product->image = $urlImage;

    return response()->json(new ProductResource($product), 201);
  }

  /**
   * Display the specified resource.
   */
  public function show(Product $product)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(Product $product)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(ProductRequest $request, Product $product)
  {
    $product->update($request->all());
    if ($request->hasFile('image')) {
      $urlImage = $product->editImage($request->image, $product, Product::path);
      $product->image = $urlImage;
    }

    return response()->json(new ProductResource($product), 201);
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Product $product)
  {
    $product->deleteImage($product, Product::path);
    $product->delete();
    return response()->json(['success' => true]);
  }
}
