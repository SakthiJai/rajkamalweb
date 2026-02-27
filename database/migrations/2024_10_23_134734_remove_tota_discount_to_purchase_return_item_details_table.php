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
           $table->dropColumn('total_discount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchase_return_item_details', function (Blueprint $table) {
             $table->double('total_discount')->nullable();
        });
    }
};
