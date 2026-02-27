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
        Schema::create('purchase_return_detalis', function (Blueprint $table) {
            $table->id();
			$table->string('dr_number', 20);
			$table->Integer(column: 'order_id');
			$table->Integer(column: 'party_id');
			$table->Integer(column: 'party_customer_id');
			$table->date(column: 'order_date');
			$table->Integer(column: 'return_by');
			$table->double('total_amount')->nullable()->default(null);
			$table->double('tax_amount')->nullable()->default(null);
			$table->double('total_discount')->nullable()->default(null);
			$table->double('total_items')->nullable()->default(null);
			$table->string('invoice_path', 20);
			$table->float('total_free', 8, 2)->default(0);
			
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_return_detalis');
    }
};
