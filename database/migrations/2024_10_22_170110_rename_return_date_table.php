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
        Schema::table('sales_return', function (Blueprint $table) {
            //
            $table->Integer( 'tax_amount');
            $table->Integer( 'total_discount');
            $table->Integer( 'total_items');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales_return', function (Blueprint $table) {
            //
        });
    }
};
