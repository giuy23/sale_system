<?php

namespace Database\Seeders;

use App\Models\Image;
use App\Models\Provider;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Laravolt\Avatar\Facade as Avatar;
use Illuminate\Support\Str;
class ProviderSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    if (Storage::exists('public/' . Provider::path)) {
      Storage::deleteDirectory('public/' . Provider::path);
    }
    Storage::makeDirectory('public/'. Provider::path);

    $provider = Provider::create([
      'name' => 'PROVEEDOR POR DEFECTO',
      'document_number' => '00000000',
      'name_company' => 'COMPAÑIA',
      'cellphone' => '000000000'
    ]);

    $avatar = Avatar::create($provider->name)->getImageObject()->encode('png');
    $filename = 'provider-default.png';
    Storage::put('public/' . Provider::path . '/' . $filename, (string) $avatar);

    Image::create([
      'url' => $filename,
      'imageable_type' => Provider::class,
      'imageable_id' => $provider->id,
    ]);

    $providers = Provider::factory(5)->create();

    foreach ($providers as $provider) {
      $imageUrl = 'https://picsum.photos/150/150?random=' . mt_rand(1, 20);
      $imageData = file_get_contents($imageUrl);

      $imageName = Str::random(8) . '.jpg';
      Storage::put('public/' . Provider::path . '/' . $imageName, $imageData);
      Image::factory(1)->create([
        'url' => $imageName,
        'imageable_type' => Provider::class,
        'imageable_id' => $provider->id,
      ]);
    }
  }
}
