<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('product_sales', function (Blueprint $table) {
      $table->id();

      $table->unsignedBigInteger('sale_id');
      $table->unsignedBigInteger('product_id');

      $table->decimal('price', 8, 2);
      $table->smallInteger('quantity');
      $table->decimal('discount', 8, 2);
      $table->decimal('total', 9, 2);
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('product_sales');
  }
};
