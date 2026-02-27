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
            $table->string('ledger_type', 121)->change(); // Changing VARCHAR(11) to VARCHAR(121)

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ledger', function (Blueprint $table) {
            $table->string('ledger_type', 11)->change();
        });
    }
};
