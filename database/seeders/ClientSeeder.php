<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    Client::create([
      'full_name' => 'CLIENTES VARIOS',
      'document_number' => "00000000",
      'cell_phone' => "000000000",
      'state' => 1,
    ]);

    Client::factory(20)->create();
  }
}
