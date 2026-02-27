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
        Schema::table('receipt_payments', function (Blueprint $table) {
           // $table->string('inst_type')->nullable()->after('voucher_number');
            //$table->string('inst_no')->nullable()->after('inst_type');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('receipt_payments', function (Blueprint $table) {
            $table->dropColumn(['inst_type', 'inst_no']);
            //
        });
    }
};
