<?php

namespace Database\Seeders;

use App\Models\Image;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $user = User::factory()->create([
      'name' => 'Admin',
      'email' => 'admin@dev.com',
      'role_id' => 1,
      'password' => Hash::make('password'),
    ]);

    if (Storage::exists('public/' . User::path)) {
      Storage::deleteDirectory('public/' . User::path);
    }
    Storage::makeDirectory('public' . '/' . User::path);

    $imageUrl = 'https://picsum.photos/150/150?random=' . mt_rand(1, 20);
    $imageData = file_get_contents($imageUrl);
    $imageName = Str::random(8) . '.jpg';
    Storage::put('public/' . User::path . '/' . $imageName, $imageData);

    Image::factory()->create([
      'url' => $imageName,
      'imageable_type' => User::class,
      'imageable_id' => $user->id,
    ]);
  }
}
