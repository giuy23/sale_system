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
        Schema::create('daily_cashes', function (Blueprint $table) {
            $table->id();
            $table->decimal('start_money', 9,2);
            $table->decimal('final_money', 9,2)->nullable();
            $table->decimal('profit', 9,2)->nullable();
            $table->boolean('state')->default(1);

            $table->unsignedBigInteger('user_id');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_cashes');
    }
};
