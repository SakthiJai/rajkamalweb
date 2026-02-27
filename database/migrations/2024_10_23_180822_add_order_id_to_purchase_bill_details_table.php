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
        Schema::table('purchase_bill_details', function (Blueprint $table) {
           $table->Integer(column: 'order_id');
		    $table->Integer(column: 'party_id');
			$table->Integer(column: 'party_customer_id');
			 $table->double('total_amount')->nullable()->default(null);
			  $table->double('tax_amount')->nullable()->default(null);
			   $table->double('total_discount')->nullable()->default(null);
			   $table->double('total_items')->nullable()->default(null);
			   $table->string(column: 'invoice_path');
		   
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchase_bill_details', function (Blueprint $table) {
            //
        });
    }
};
