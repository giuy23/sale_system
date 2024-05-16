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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->decimal('sub_total', 9,2);
            $table->decimal('discount_total', 9,2);
            $table->decimal('total', 9,2);
            $table->string('igv')->comment('18%');
            $table->char('state', 1)->comment('[1=>pagado,2=>cancelado,3=>crédito]');

            $table->unsignedBigInteger('user_id')->comment('cliente');
            $table->unsignedBigInteger('seller_id');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
