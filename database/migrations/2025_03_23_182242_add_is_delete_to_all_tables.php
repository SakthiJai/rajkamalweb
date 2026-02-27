<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // List of all 101 tables from your schema

    // add aphabet A to C tables in next migration like these
    protected $tables = [

  'expenses_items', 'expenses_item_detalis', 'expense_categories',
        'failed_jobs', 'front_product_cards', 'front_website_settings', 'geolocation', 'god_owns',
        'holidays', 'hsc_sac', 'increments_promotions', 'jobs', 'langs',
        'leaves', 'leave_types', 'ledger_item', 'ledger_mobile',
        'migrations', 'notifications', 'order_custom_fields', 'order_items',
        'order_item_taxes', 'order_payments', 'order_shipping_address', 'party_customers_details', 'party_details',
        'payments', 'payment_mode', 'payment_modes', 'payrolls', 'payroll_components',
        'permissions', 'permission_role', 'pre_payments', 'products', 'product_category',
        'product_company', 'product_custom_fields', 'product_details', 'product_variants', 'purchase_bill_details',
        'purchase_bill_items_details', 'purchase_return_detalis', 'purchase_return_item_details', 'receipt', 'receiptpayment_items',
        'receipt_accounttrans', 'receipt_bank_account', 'receipt_items', 'receipt_payments', 'receipt_paymentsitems',
        'return_reason', 'roles', 'role_user', 'sales_return', 'sales_returns',
        'sales_return_items', 'settings', 'shifts', 'shipping_details', 'states',
        'station', 'stock_adjustments', 'stock_history', 'subscription_plans', 'taxes',
        'tax_catagories', 'translations', 'units', 'users', 'user_address',
        'user_details', 'user_warehouse', 'variations', 'warehouses', 'warehouse_history',
        'warehouse_stocks'
    ];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        
    }
};
