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
        Schema::table('expenses1', function (Blueprint $table) {
           // $table->renameColumn('entry_date', 'order_date');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('expenses1', function (Blueprint $table) {
            $table->renameColumn('order_date', 'entry_date');

        });
    }
};
