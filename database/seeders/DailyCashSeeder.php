<?php

namespace Database\Seeders;

use App\Models\DailyCash;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DailyCashSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('email', 'admin@dev.com')->first();

        DailyCash::factory(20)->create([
          'user_id' => $admin->id,
        ]);
    }
}
