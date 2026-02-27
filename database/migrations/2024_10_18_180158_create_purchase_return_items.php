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
        Schema::create('purchase_return_items', function (Blueprint $table) {
            $table->id();
            $table->String(column: 'cr_number');
            $table->Integer(column: 'order_id');
            $table->Integer(column: 'product_id');
            $table->Integer(column: 'unit_id')->nullable()->change();
            $table->Integer(column: 'quantity')->nullable()->change();
            $table->Integer(column: 'mrp')->nullable()->change();
            $table->Integer(column: 'unit_price')->nullable()->change();
            $table->Integer(column: 'single_unit_price')->nullable()->change();
            $table->Integer(column: 'tax_id')->nullable()->change();
            $table->Integer(column: 'tax_rate')->nullable()->change();
            $table->Integer(column: 'tax_type')->nullable()->change();
            $table->Integer(column: 'discount_rate')->nullable()->change();
            $table->Integer(column: 'total_tax')->nullable()->change();
            $table->Integer(column: 'total_discount')->nullable()->change();
            $table->Integer(column: 'subtotal')->nullable()->change();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_return_items');
    }
};
