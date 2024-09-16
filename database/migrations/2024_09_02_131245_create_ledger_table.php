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
        Schema::create('ledger', function (Blueprint $table) {
            $table->id();
            $table->string('party_name',250);
            $table->string('station',250);
            $table->longText('Address')->nullable();
            $table->integer('country');
            $table->integer('state');
            $table->integer('city');
            $table->text('parent_ledger')->nullable();
            $table->string('account_group');
            $table->string('mail_to')->nullable();
            $table->integer('pincode')->nullable();
            $table->string('balancing_method')->nullable();
            $table->integer('opening_balance')->nullable();
            $table->integer('credit_days')->nullable();
            $table->string('Phone_no',25)->nullable();
            $table->string('Mobile_no',25);
            $table->string('whatsapp',25)->nullable();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ledger');
    }
};
