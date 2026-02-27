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
        Schema::table('purchase_return_item_details', function (Blueprint $table) {
           $table->string('dr_number', 20);
		   $table->Integer(column: 'order_id');
		   $table->Integer(column: 'tax_id');
		   $table->double('tax_rate')->nullable()->default(null);
		   $table->string(column: 'tax_type');
		   $table->double('total_tax')->nullable()->default(null);
		   $table->double('total_discount')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchase_return_item_details', function (Blueprint $table) {
            //
        });
    }
};
