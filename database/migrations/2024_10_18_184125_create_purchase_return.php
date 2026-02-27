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
        Schema::create('sales_return', function (Blueprint $table) {
            $table->id();
           
			$table->String(column: 'cr_number');
            $table->Integer(column: 'order_id');
			$table->Integer(column: 'party_id');
            $table->Integer(column: 'party_customer_id');
            $table->dateTime(column: 'return_date_time');
            $table->dateTime(column: 'description')->nullable();
            $table->tinyInteger(column: 'return_status')->nullable()->change();
            $table->dateTime(column: 'return_by');            
            $table->timestamps();
            $table->unique('cr_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_return');
    }
};
