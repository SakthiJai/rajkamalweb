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
            $table->integer('ledger_type')->default(0)->nullable()->after('whatsapp_number');
            $table->bigInteger('pan_number')->nullable()->after('ledger_type');
            $table->integer('customer_title')->default(0)->nullable()->after('pan_number');
            $table->string('customer_first_name')->nullable()->after('customer_title');
            $table->string('customer_last_name')->nullable()->after('customer_first_name');
            $table->integer('gender')->default(0)->nullable()->after('customer_last_name');
            $table->string('designation')->nullable()->after('gender');
            $table->string('website')->nullable()->after('designation');
            $table->string('customer_email')->nullable()->after('website');
            $table->string('bank_name')->nullable()->after('customer_email');
            $table->string('branch')->nullable()->after('bank_name');
            $table->string('ifsc_code')->nullable()->after('branch');
            $table->string('account_number')->nullable()->after('ifsc_code');
            $table->integer('account_type')->default(0)->nullable()->after('account_number');
            $table->string('account_holder_name')->nullable()->after('account_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ledger', function (Blueprint $table) {
            $table->dropColumn([
                'ledger_type', 'pan_number', 'customer_title', 'customer_first_name', 
                'customer_last_name', 'gender', 'designation', 'website', 'customer_email',
                'bank_name', 'branch', 'ifsc_code', 'account_number', 'account_type', 
                'account_holder_name'
            ]);
        });
    }
};
