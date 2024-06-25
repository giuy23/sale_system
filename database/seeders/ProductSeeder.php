<?php

namespace Database\Seeders;

use App\Models\Image;
use App\Models\Product;
use App\Models\Provider;
use App\Models\SubCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $providers = Provider::all();
    $subCategories = SubCategory::all();

    if (Storage::exists('public' . '/' . Product::path)) {
      Storage::deleteDirectory('public' . '/' . Product::path);
    }
    Storage::makeDirectory('public' . '/' . Product::path);


    foreach ($providers as $provider) {
      $products = Product::factory(6)->create([
        'sub_category_id' => $subCategories->random()->id,
        'provider_id' => $provider->id,
      ]);

      foreach ($products as $product) {
        $imageUrl = 'https://picsum.photos/150/150?random=' . mt_rand(1, 20);
        $imageData = file_get_contents($imageUrl);
        $imageName = Str::random(8) . '.jpg';
        Storage::put('public/' . Product::path . '/' . $imageName, $imageData);

        for ($i = 0; $i < 1; $i++) {
          Image::factory()->create([
            'url' => $imageName,
            'imageable_type' => Product::class,
            'imageable_id' => $product->id,
            'is_principal' => $i == 0 ? 1 : 0
          ]);
        }
      }
    }
  }
}
