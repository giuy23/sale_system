<?php

namespace Database\Seeders;

use App\Models\Image;
use App\Models\Provider;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProviderSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $providers = Provider::factory(5)->create();

    $folderPath = 'public/images/providers';

    if (Storage::exists($folderPath)) {
      Storage::deleteDirectory($folderPath);
    }
    Storage::makeDirectory($folderPath);

    foreach ($providers as $provider) {
      $imageUrl = 'https://picsum.photos/150/150?random=' . mt_rand(1, 20);
      $imageData = file_get_contents($imageUrl);

      $imageName = Str::random(8) . '.jpg';
      Storage::put($folderPath . '/' . $imageName, $imageData);
      Image::factory(1)->create([
        'url' => $imageName,
        'imageable_type' => Provider::class,
        'imageable_id' => $provider->id,
      ]);
    }
  }
}
