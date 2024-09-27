<?php

namespace App\Models;

use App\Casts\Hash;
use App\Classes\Common;
use App\Models\BaseModel;
use App\Scopes\CompanyScope;
class Order extends BaseModel
{
    protected $table = 'orders';
    protected $default = [
        'xid',
        'bill_number',
        'address',
        
    ]; 
    protected $guarded = ['id', 'warehouse_id', 'staff_user_id', 'order_type', 'party_customer_id', 'created_at', 'updated_at'];

    protected $hidden = ['warehouse_id','party_id', 'product_id','payment_id','from_warehouse_id', 'user_id', 'tax_id', 'staff_user_id', 'cancelled_by'];

    protected $appends = ['xid', 'x_warehouse_id','party_id','product_id','payment_id', 'x_from_warehouse_id', 'x_user_id', 'x_tax_id', 'x_staff_user_id', 'x_cancelled_by', 'document_url'];

    protected $dates = ['order_date'];

    protected $filterable = ['id', 'bill_number','address','payment_status','invoice_number', 'order_status', 'cancelled', 'order_date', 'user_id', 'warehouse_id', 'staff_user_id','party_id','product_id','payment_id',];
  
    protected $hashableGetterFunctions = [
        'getXWarehouseIdAttribute' => 'warehouse_id',
        'getXFromWarehouseIdAttribute' => 'from_warehouse_id',
        'getXUserIdAttribute' => 'user_id',
        'getXTaxIdAttribute' => 'tax_id',
        'getXStaffUserIdAttribute' => 'staff_user_id',
        'getXCancelledByAttribute' => 'cancelled_by',
    ];

    protected $casts = [
        'order_date' => 'datetime',
        'warehouse_id' => Hash::class . ':hash',
        'user_id' => Hash::class . ':hash',
        'tax_id' => Hash::class . ':hash',
        'cancelled_by' => Hash::class . ':hash',
        'cancelled' => 'integer',
        'is_deletable' => 'integer',
        'tax_rate' => 'double',
        'tax_amount' => 'double',
        'discount' => 'double',
        'shipping' => 'double',
        'subtotal' => 'double',
        'total' => 'double',
        'paid_amount' => 'double',
        'due_amount' => 'double',
        'total_items' => 'double',
        'total_quantity' => 'double',
        'bill_number' => 'string',
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new CompanyScope);
    }

    public function getDocumentUrlAttribute()
    {
        $orderDocumentPath = Common::getFolderPath('orderDocumentPath');

        return $this->image == null ? asset('images/order.png') : Common::getFileUrl($orderDocumentPath, $this->image);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id', localKey: 'id');
    }
    public function partyName()
    {
       return $this->belongsTo(LedgerModel::class, 'party_id', 'id');
    }

    public function productName()
    {
       return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function paymentName()
    {
       return $this->belongsTo(PaymentModeModel::class, 'payment_id', 'id');
    }

    public function customer()
    {
        return $this->belongsTo(LedgerCustomerModel::class,'party_customer_id', 'id');
    }

    public function orderPayments()
    {
        return $this->hasMany(OrderPayment::class, 'order_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(StaffMember::class, 'user_id', 'id')->withoutGlobalScope('type');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'user_id', ownerKey: 'id');
    }

    public function staffMember()
    {
        return $this->belongsTo(StaffMember::class, 'staff_user_id', 'id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id', 'id');
    }

    public function fromWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'from_warehouse_id', 'id');
    }

    public function tax()
    {
        return $this->belongsTo(Tax::class, 'tax_id', 'id');
    }

    public function shippingAddress()
    {
        return $this->belongsTo(OrderShippingAddress::class, 'id', 'order_id');
    }
    
}
