<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\BaseModel;
use App\Models\LedgerModel;
use App\Models\LedgerCustomerModel;
use App\Models\OrderPayment;
use App\Models\StaffMember;
use App\Models\SalesReturnItems;

class SalesReturn extends BaseModel
{
    use HasFactory;

    protected $table = 'sales_return';

    protected $default = [
        'xid',
        'id',
        'unique_id',
        'cr_number',
        'order_id',
        'party_id',
        'party_customer_id',
        'order_date',
        'description',
        'return_by',
        'login_user_id',
        'total_amount',
        'tax_amount',
        'total_discount',
        'total_items',
        'invoice_path',
        'return_mode',
        'reason',
        'return_qty',
        'invoice_number',
        'total_quantity',
        'party_name'
    ];

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $appends = [
        'xid',
        'party_name',
        'invoice_number',
        'bill_number',
        'total',
        'discount',
        'subtotal',
        'paid_amount',
        'due_amount',
        'payment_status',
        'order_type',
        'total_quantity',
    ];

    protected $casts = [
        'order_date' => 'datetime',
        'total_amount' => 'double',
        'tax_amount' => 'double',
        'total_discount' => 'double',
        'total_items' => 'double',
    ];

    protected $allowedFilters = [ 'cr_number','order_id' ];

    protected $filterable = ['id', 'cr_number', 'order_id'];

    public function user()
    {
        return $this->belongsTo(StaffMember::class, 'return_by', 'id')->withoutGlobalScope('type');
    }
    public function getPartyNameAttribute()
     {

         $partyname = LedgerModel::find(id: $this->party_id);
         return $partyname ? $partyname->party_name : 'Unknown';
     }
    public function partyName()
    {
       return $this->belongsTo(LedgerModel::class, 'party_id', 'id');
    }

    public function party()
    {
       return $this->belongsTo(LedgerModel::class, 'party_id', 'id');
    }

    public function customer()
    {
        return $this->belongsTo(LedgerCustomerModel::class,'party_customer_id', 'id');
    }
    public function items()
    {
        return $this->hasMany(SalesReturnItems::class,'order_id', 'id');
    }

    public function orderPayments()
    {
        // Sales returns are stored outside the orders table, but shared UI/API
        // field sets still request orderPayments for this resource.
        return $this->hasMany(OrderPayment::class, 'order_id', 'id')->whereRaw('1 = 0');
    }

    public function getInvoiceNumberAttribute()
    {
        return $this->cr_number;
    }

    public function getBillNumberAttribute()
    {
        return $this->cr_number;
    }

    public function getTotalAttribute()
    {
        return $this->total_amount ?? 0;
    }

    public function getDiscountAttribute()
    {
        return $this->total_discount ?? 0;
    }

    public function getSubtotalAttribute()
    {
        return $this->total_amount ?? 0;
    }

    public function getPaidAmountAttribute()
    {
        return 0;
    }

    public function getDueAmountAttribute()
    {
        return $this->total_amount ?? 0;
    }

    public function getPaymentStatusAttribute()
    {
        return 'unpaid';
    }

    public function getOrderTypeAttribute()
    {
        return 'sales-returns';
    }

    public function getTotalQuantityAttribute()
    {
        return $this->items()->sum('return_qty');
    }
}
