<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ProductToSellResource;
use App\Models\Product;
use App\Models\Sale;
use App\Traits\ImageTrait;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductController extends Controller
{
  use ImageTrait;
  public function index(Request $request)
  {
    $products = Product::query()->filterData($request)
      ->when($request->filled('search'), function ($query) use ($request) {
        $search = $request->search;
        $query->orWhere('bar_code', 'like', '%' . $search . '%');
      })
      ->paginate(15);

    if ($request->wantsJson()) {
      return ProductResource::collection($products)->response();
    }

    return Inertia::render('views/ProductView', [
      'products' => ProductResource::collection($products),
    ]);
  }
  public function store(ProductRequest $request)
  {
    $product = Product::create($request->all());
    $urlImage = $this->uploadImage($request->image, $product, Product::path, true);
    $product->image = $urlImage;
    $product['state'] = 1;

    return response()->json(new ProductResource($product), 201);
  }
  public function update(ProductRequest $request, Product $product)
  {
    $product->update($request->all());
    if ($request->hasFile('image')) {
      $urlImage = $product->editImage($request->image, $product, Product::path);
      $product->image = $urlImage;
    }

    return response()->json(new ProductResource($product), 201);
  }

  public function changeState(Request $request, Product $product)
  {
    $request->validate(['state' => 'required', 'boolean']);
    $product->update(['state' => $request->state]);

    $productUpdate = Product::find($product->id);
    return response()->json(new ProductResource($productUpdate));
  }

  public function destroy(Product $product)
  {
    $product->deleteImage($product, Product::path);
    $product->delete();
    return response()->json(['success' => true]);
  }

  public function productsToSell(Request $request)
  {
    $products = Product::where('state', 1)
      ->where('quantity', '>', 0)
      ->when($request->filled('value'), function ($query) use ($request) {
        $search = $request['value'];
        $query->where(function ($query) use ($search) {
          $query->where('name', 'like', '%' . $search . '%')
            ->orWhere('bar_code', 'like', '%' . $search . '%');
        });
      })
      ->get();

    return response()->json(ProductToSellResource::collection($products));
  }

  public function getBestSellingProducts(Request $request)
  {
    $request->validate(['date' => 'required', 'string']);
    [$year, $month] = explode('-', $request['date']);

    $products = Product::select(['id', 'name'])->withCount([
      'sales as sales_count' => function ($query) use ($year, $month) {
        $query->whereYear('sales.created_at', $year)
          ->whereMonth('sales.created_at', $month);
      }
    ])
      ->whereHas('sales', function ($query) use ($year, $month) {
        $query->whereYear('sales.created_at', $year)
          ->whereMonth('sales.created_at', $month);
      })
      ->orderByDesc('sales_count')
      ->limit(15)->get();

    return response()->json($products);
  }

  public function getProductsToRestock()
  {
    $products = Product::select(['id', 'name', 'quantity'])
      ->whereRaw('quantity < minimum_quantity')
      ->where('state', 1)
      ->orderBy('quantity')
      ->get();

    return response()->json($products);
  }
}
