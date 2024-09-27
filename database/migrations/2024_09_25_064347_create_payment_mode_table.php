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
        Schema::create('payment_mode', function (Blueprint $table) {
            $table->id();
			 $table->string( 'bill_amount')->nullable();
			 $table->string( 'balance_adjusted')->nullable();
			 $table->string( 'settlement_mode')->nullable();
			 $table->string( 'amount')->nullable();
			 $table->text( 'remarks')->nullable();
			 $table->string( 'cash_tender')->nullable();
			 $table->string( 'cash_return')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_mode');
    }
};
