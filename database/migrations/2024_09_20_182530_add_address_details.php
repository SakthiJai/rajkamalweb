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
        Schema::table('party_customers_details', function (Blueprint $table) {
            //
            $table->text( 'address')->nullable();
            $table->text( 'email')->nullable();
            $table->string( 'whatsapp_numbner',12)->nullable();
            $table->string( 'dob',12)->nullable();
            $table->text(  'government_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('party_customers_details', function (Blueprint $table) {
            //
            $table->text( 'address')->nullable();
            $table->text( 'email')->nullable();
            $table->string( 'whatsapp_numbner',12)->nullable();
            $table->string( 'dob',12)->nullable();
            $table->text(  'government_id')->nullable();
        });
    }
};
