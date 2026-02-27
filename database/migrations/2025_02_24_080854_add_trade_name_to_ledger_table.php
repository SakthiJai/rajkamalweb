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
        Schema::table('ledger', function (Blueprint $table) {
           // $table->string('trade_name')->nullable()->after('party_name');


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ledger', function (Blueprint $table) {
          //  $table->dropColumn('trade_name');

        });
    }
};
