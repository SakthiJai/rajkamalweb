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
               Schema::create('purchase_return_item_details', function (Blueprint $table) {
            $table->id();
			 $table->integer(column: 'user_id');
			  $table->integer(column: 'purchase_id');
			  $table->integer(column: 'unit_id');
			  $table->double('quantity');
			   $table->double('free');
			  $table->double('mrp');
			  $table->double('unit_price');
			  $table->double('single_unit_price');
			  $table->double('discount_rate');
			  $table->double('subtotal');
				
			  
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_return_item_detalis');
    }
};
