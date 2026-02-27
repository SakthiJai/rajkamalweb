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
        Schema::table('sales_return_items', function (Blueprint $table) {
            //
            $table->Integer( 'unit_id')->nullable();
            $table->Integer( 'unit_price')->nullable();
            $table->double( 'quantity')->nullable();
            $table->double( 'mrp')->nullable();
            $table->double( 'single_unit_price')->nullable();
            $table->Integer( 'tax_id')->nullable();
            $table->double( 'tax_rate')->nullable();
            $table->String( 'tax_type')->nullable();
            $table->double( 'discount_rate')->nullable();
            $table->double( 'total_tax')->nullable();
            $table->double( 'total_discount')->nullable();
            $table->double( 'subtotal')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales_return_items', function (Blueprint $table) {
            //
        });
    }
};
