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
        Schema::table('purchase_bill_items_details', function (Blueprint $table) {
          
		  $table->Integer(column: 'unit_id');
		  $table->double('quantity')->nullable()->default(null);
		  $table->double('free')->nullable()->default(null);
		  $table->double('mrp')->nullable()->default(null);
		   $table->double('unit_price')->nullable()->default(null);
		   $table->double('single_unit_price')->nullable()->default(null);
		   $table->double('	discount_rate')->nullable()->default(null);
		    $table->double('subtotal')->nullable()->default(null);
			$table->Integer(column: 'product_id');
			$table->Integer(column: 'order_id');
			$table->Integer(column:'tax_id');
			  $table->double('tax_rate')->nullable()->default(null);
				$table->string(column:'tax_type');
			   $table->double('total_tax')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchase_bill_items_details', function (Blueprint $table) {
            //
        });
    }
};
