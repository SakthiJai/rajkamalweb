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
        Schema::table('payment_mode', function (Blueprint $table) {
            $table->string(column: 'payment_type');
            $table->string(column: 'payment_added_by');
            $table->timestamp(column: 'payment_added_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payment_mode', function (Blueprint $table) {
            //
        });
    }
};
