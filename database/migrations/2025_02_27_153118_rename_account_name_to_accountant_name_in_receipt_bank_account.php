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
        Schema::table('receipt_bank_account', function (Blueprint $table) {
           // $table->renameColumn('account_name', 'accountant_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('receipt_bank_account', function (Blueprint $table) {
           // $table->renameColumn('accountant_name', 'account_name');
        });
    }
};
