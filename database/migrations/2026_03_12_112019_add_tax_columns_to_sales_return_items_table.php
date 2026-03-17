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
        Schema::table('sales_return_items', function (Blueprint $table) {
            $table->decimal('cgst', 8, 2)->default(0)->after('subtotal');
            $table->decimal('sgst', 8, 2)->default(0)->after('cgst');
            $table->decimal('igst', 8, 2)->default(0)->after('sgst');
            $table->decimal('cess', 8, 2)->default(0)->after('igst');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales_return_items', function (Blueprint $table) {
            $table->dropColumn(['cgst','sgst','igst','cess']);
        });
    }
};
