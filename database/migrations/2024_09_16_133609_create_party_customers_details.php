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
        Schema::create('party_customers_details', function (Blueprint $table) {
            $table->id();
            
            $table->Integer(column: 'ledger_id')->nullable();
            $table->string(column: 'name');
            $table->string(column: 'mobile_number');
            $table->string(column: 'gender')->nullable()->change();
            $table->smallInteger('gender')->nullable();
            $table->smallInteger(column: 'age')->nullable();
            $table->string(column: 'address')->nullable()->change();
            $table->string(column: 'email_id')->nullable()->change();
            $table->string(column: 'whatsapp_number')->nullable()->change();
            $table->string(column: 'dob')->nullable()->change();
            $table->smallInteger(column: 'billing_discount')->nullable();
            $table->smallInteger(column: 'customer_type')->nullable();
            $table->string(column: 'id_proof')->nullable()->change();          
            $table->smallInteger(column: 'customer_status')->nullable()->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('party_customers_details');
    }
};
