<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Auth;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DailyCash>
 */
class DailyCashFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    $startMoney = fake()->numberBetween(150, 500);
    $finalMoney = $startMoney + fake()->numberBetween(300, 350);
    static $dayCounter = 19;

    $createdAt = Carbon::now()->subDays($dayCounter)->startOfDay();
    $updatedAt = Carbon::now()->subDays($dayCounter)->endOfDay();
    $state = ($dayCounter == 0) ? 1 : 0;

    $dayCounter--;

    return [
      'start_money' => $startMoney,
      'final_money' => $finalMoney,
      'profit' => $finalMoney - $startMoney,
      'state' => $state,
      'created_at' => $createdAt,
      'updated_at' => $updatedAt,
    ];
  }
}
