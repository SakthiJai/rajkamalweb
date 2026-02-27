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
        Schema::create('expenses_item_detalis', function (Blueprint $table) {
            $table->id();
			 $table->integer('user_id');
            $table->integer('order_id');
            $table->integer('product_id');
            $table->string('product_name');
            $table->integer('unit_id')->nullable();
            $table->integer('quantity')->nullable();
            $table->integer('rate')->nullable();
            $table->integer('mrp')->nullable();
            $table->integer('unit_price')->nullable();
            $table->integer('single_unit_price')->nullable();
            $table->integer('tax_id')->nullable();
            $table->integer('tax_rate')->nullable();
            $table->integer('tax_type')->nullable();
            $table->integer('discount_rate')->nullable();
            $table->integer('total_tax')->nullable();
            $table->integer('total_discount')->nullable();
            $table->integer('subtotal')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses_item_detalis');
    }
};
