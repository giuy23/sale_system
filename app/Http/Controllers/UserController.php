<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\ImageTrait;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
  use ImageTrait;
  public function index(Request $request)
  {
    $users = User::query()->filterData($request)
      ->when($request->filled('search'), function ($query) use ($request) {
        $search = $request->search;
        $query->orWhere('sur_name', 'like', '%' . $search . '%');
        $query->orWhere('document_number', 'like', '%' . $search . '%');
        $query->orWhere('email', 'like', '%' . $search . '%');
      })->paginate(15);

    if ($request->wantsJson()) {
      return UserResource::collection($users)->response();
    }

    return Inertia::render('views/UserView', [
      'users' => UserResource::collection($users),
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
  public function store(UserRequest $request)
  {
    $user = User::create($request->all());
    if ($request->hasFile('image')) {
      $urlImage = $this->uploadImage($request->image, $user, User::path, false);
      $user->image = $urlImage;
    }

    return response()->json(new UserResource($user), 201);
  }

  /**
   * Display the specified resource.
   */
  public function show(string $id)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(string $id)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(UserRequest $request, User $user)
  {
    $user->update($request->all());
    if ($request->hasFile('image')) {
      $urlImage = $this->editImage($request->image, $user, User::path);
      $user->image = $urlImage;
    }

    return response()->json(new UserResource($user));
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(User $user)
  {
    $this->deleteImage($user, User::path);
    $user->delete();

    return response()->json(['success' => true]);
  }
}