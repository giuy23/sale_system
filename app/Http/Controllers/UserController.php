<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\ImageTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
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
    } else {
      $urlImage = $this->createImage($user, User::path);
      $user->image = $urlImage;
    }
    $user['state'] = 1;

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

  public function changeState(Request $request, User $user)
  {
    $request->validate(['state' => ['required', 'boolean']]);
    $user->update(['state' => $request->state]);
    $updatedUser = User::find($user->id);
    $user['state'] = 1;

    return response()->json(new UserResource($updatedUser));
  }

  public function update(UserRequest $request, User $user)
  {
    $user->update($request->all());
    if ($request->hasFile('image')) {
      $urlImage = $this->editImage($request->image, $user, User::path);
      $user->image = $urlImage;
    }

    return response()->json(new UserResource($user));
  }

  public function updateImage(Request $request)
  {
    $request->validate(['image' => ['required', 'image', 'max:4096']]);
    $idUser = Auth::id();
    $user = User::findOrFail($idUser);

    if ($request->hasFile('image')) {
      $urlImage = $user->editImage($request->image, $user, User::path);
      $user->image = $urlImage;
    }

    return response()->json(['success' => true], 201);
  }

  public function updatePassword(Request $request)
  {
    $validated = $request->validate([
      'current_password' => ['required', 'current_password'],
      'password' => ['required', Password::defaults(), 'confirmed'],
    ]);

    $request->user()->update([
      'password' => Hash::make($validated['password']),
    ]);

    return response()->json([], 200);
    // return back();
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
