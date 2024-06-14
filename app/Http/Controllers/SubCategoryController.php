<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubCategoryRequest;
use App\Http\Resources\SubCategoryResource;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SubCategoryController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index(Request $request)
  {
    $subCategories = SubCategory::query()
      ->filterData($request)
      ->when($request->filled('search'), function ($query) use ($request) {
        $search = $request->search;
        $query->orWhereHas('category', function ($query) use ($search) {
          $query->where('name', 'like', '%' . $search . '%');
        });
      })
      ->paginate(15);

    if ($request->wantsJson()) {
      // dd($subCategories);
      return SubCategoryResource::collection($subCategories)->response();
    }

    return Inertia::render('views/SubCategoryView', [
      'subCategories' => SubCategoryResource::collection($subCategories)
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
  public function store(SubCategoryRequest $request)
  {
    $subCategory = SubCategory::create($request->all());

    return response()->json(new SubCategoryResource($subCategory), 201);
  }

  /**
   * Display the specified resource.
   */
  public function show(SubCategory $subCategory)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(SubCategory $subCategory)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(SubCategoryRequest $request, SubCategory $subCategory)
  {
    $subCategory->update($request->all());

    return response()->json(new SubCategoryResource($subCategory));
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(SubCategory $subCategory)
  {
    $subCategory->delete();

    return response()->json(['success' => true]);
  }
}
