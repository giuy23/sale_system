<?php

namespace Database\Seeders;

use App\Models\Enterprise;
use App\Models\Image;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EnterpriseSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $enterprise = Enterprise::create([
      'name' => 'HORUS LUPERCAL',
      'name_comercial' => 'HORUS LUPERCAL E.I.R.L.',
      'description' => 'Empresa comprometida con ofrecer los precios más bajos del mercado y de primera calidad.',
      'cell_phone' => 987654321,
      'address' => 'Av. Cualquiera - Calle Cualquiera 123',
      'RUC' => 12234567890,
    ]);

    if (Storage::exists('public' . '/' . Enterprise::path)) {
      Storage::deleteDirectory('public' . '/' . Enterprise::path);
    }
    Storage::makeDirectory('public' . '/' . Enterprise::path);

    $imageUrl = 'https://www.zarla.com/images/zarla-msco-mida-1x1-2400x2400-20220329-79bv8gfcpjgv8hggc7r4.png?crop=1:1,smart&width=250&dpr=2' . mt_rand(1, 20);
    $imageData = file_get_contents($imageUrl);
    $imageName = Str::random(8) . '.png';
    Storage::put('public/' . Enterprise::path . '/' . $imageName, $imageData);

    Image::create([
      'url' => $imageName,
      'imageable_id' => $enterprise->id,
      'imageable_type' => Enterprise::class,
    ]);

  }
}
