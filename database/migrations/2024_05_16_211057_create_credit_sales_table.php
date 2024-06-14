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
        Schema::create('credit_sales', function (Blueprint $table) {
            $table->id();
            $table->decimal('amount_payable', 9,2);
            $table->decimal('remaining_amount', 9,2);
            $table->text('description')->nullable();
            $table->boolean('is_paid')->default(0)->comment('[0=>debe,1=>pagado]');

            $table->unsignedBigInteger('sale_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credit_sales');
    }
};
