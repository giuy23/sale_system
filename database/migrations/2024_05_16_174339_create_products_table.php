<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name',180);
            $table->text('description')->nullable();
            $table->decimal('purchase_price', 8,2);
            $table->decimal('sale_price', 8,2);
            $table->string('bar_code', 21)->nullable();
            $table->smallInteger('quantity');
            $table->smallInteger('minimum_quantity')->default(1);
            $table->boolean('state')->default(1)->comment('[1=>activo, 0=>inactivo]');

            $table->unsignedBigInteger('sub_category_id');
            $table->unsignedBigInteger('provider_id');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
