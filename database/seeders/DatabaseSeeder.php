<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   */
  public function run(): void
  {
    User::factory()->create([
      'name' => 'Admin',
      'email' => 'admin@dev.com',
      'password' => Hash::make('password'),
    ]);

    $this->call(
      [
        CategorySeeder::class,
        SubCategorySeeder::class,
        ProviderSeeder::class,
        ProductSeeder::class,
        DailyCashSeeder::class,
        ]
      );
  }
}
