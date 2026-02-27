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
        Schema::table('purchase_return_detalie', function (Blueprint $table) {
           $table->Integer(column: 'party_id');
            $table->Integer(column: 'party_customer_id');
            $table->dateTime(column: 'return_date_time');
			$table->float('tax_rate', 8, 2)->nullable()->default(null);
			$table->double('tax_amount')->default(0);
			$table->double('discount')->nullable()->default(null);
			$table->double('shipping')->nullable()->default(null);
			$table->double('subtotal');
			$table->double('total');
			$table->double('paid_amount')->default(0);
			$table->double('due_amount')->default(0);
			$table->float('total_items', 8, 2)->default(0);
			$table->float('total_quantity', 8, 2)->default(0);
			$table->float('total_free', 8, 2)->default(0);
			
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchase_return_detalie', function (Blueprint $table) {
            //
        });
    }
};
