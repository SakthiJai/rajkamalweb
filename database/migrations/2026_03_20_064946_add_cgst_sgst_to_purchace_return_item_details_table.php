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
            $table->decimal('cgst', 10, 2)->default(0)->after('mrp');
            $table->decimal('sgst', 10, 2)->default(0)->after('cgst');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchase_return_item_details', function (Blueprint $table) {
            $table->dropColumn(['cgst', 'sgst']);
        });
    }
};
