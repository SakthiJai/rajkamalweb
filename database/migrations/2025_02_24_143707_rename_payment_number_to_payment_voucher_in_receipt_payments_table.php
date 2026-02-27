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
           // $table->renameColumn('payment_number', 'payment_voucher');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('receipt_payments', function (Blueprint $table) {
          //  $table->renameColumn('payment_voucher', 'payment_number');
        });
    }
};
