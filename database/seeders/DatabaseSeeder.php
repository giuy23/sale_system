<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Image;
use App\Models\Provider;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Laravolt\Avatar\Facade as Avatar;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   */
  public function run(): void
  {
    $this->call(
      [
        EnterpriseSeeder::class,
        RoleSeeder::class,
        UserSeeder::class,
        // CategorySeeder::class,
        // SubCategorySeeder::class,
        // ProviderSeeder::class,
        // ProductSeeder::class,
        // DailyCashSeeder::class,
        ClientSeeder::class,
      ]
    );

    if (Storage::exists('public/' . Provider::path)) {
      Storage::deleteDirectory('public/' . Provider::path);
    }
    Storage::makeDirectory('public/' . Provider::path);

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
  }
}
