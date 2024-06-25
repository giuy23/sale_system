<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CategoryController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index(Request $request)
  {
    $categories = Category::query()->filterData($request)->paginate(15);

    if ($request->wantsJson()) {
      return CategoryResource::collection($categories)->response();
    }

    return Inertia::render('views/CategoryView', [
      'categories' => CategoryResource::collection($categories),
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
  public function store(CategoryRequest $request)
  {
    $category = Category::create($request->all());
    return response()->json(new CategoryResource($category), 201);
  }

  /**
   * Display the specified resource.
   */
  public function show(Category $category)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(Category $category)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(CategoryRequest $request, Category $category)
  {
    $category->update($request->all());
    return response()->json(new CategoryResource($category));
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Category $category)
  {
    $category->subCategories()->delete();
    $category->delete();
    return response()->json(['success' => true]);
  }
}
