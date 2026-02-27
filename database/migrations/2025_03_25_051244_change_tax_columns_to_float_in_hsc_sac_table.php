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
        Schema::table('hsc_sac', function (Blueprint $table) {
            $table->float('gst')->change();
            $table->float('cgst')->change();
            $table->float('sgst')->change();
            $table->float('cess')->change();
            $table->float('lgst')->change();


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hsc_sac', function (Blueprint $table) {
            $table->integer('gst')->change();
            $table->integer('cgst')->change();
            $table->integer('sgst')->change();
            $table->integer('cess')->change();
            $table->integer('lgst')->change();

        });
    }
};
