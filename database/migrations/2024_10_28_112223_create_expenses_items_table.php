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
        Schema::create('expenses_items', function (Blueprint $table) {
            $table->id();
			$table->Integer(column: 'party_id');
			$table->Integer(column: 'party_customer_id');
			$table->date(column: 'order_date');
			$table->string(column: 'expense_category');
			$table->string(column: 'expense_number');
			$table->string(column: 'original_invoice_number');
			$table->Integer(column: 'payment_mode');
			$table->Integer(column: 'payment_mode_from');
			$table->string(column: 'address');
			$table->double('total_amount')->nullable()->default(null);
			$table->double('tax_amount')->nullable()->default(null);
			$table->double('total_discount')->nullable()->default(null);
			$table->double('total_items')->nullable()->default(null);
  $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses_items');
    }
};
