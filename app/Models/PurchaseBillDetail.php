<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\BaseModel;

// use App\Http\Requests\Api\Purchases\SalesCreateRequest;


class PurchaseBillDetail extends BaseModel
{
    use HasFactory;
    protected $table = 'purchase_bill_details';

    protected $default = ['xid','id','invoice_number','invoice_date','order_id','party_id','party_customer_id', 'order_date','return_by'];

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $appends = ['xid','party_name'];
    
    protected $allowedFilters = [ 'invoice_number','order_id' ];
    
    protected $filterable = ['id', 'cus_name','invoice_number','order_id'];

    protected $casts = [
        'total_amount' => 'double',
        'tax_amount' => 'double',
        'total_discount' => 'double',
        'total_items' => 'double',
        'total_quantity' => 'double',
        'total_free' => 'double',
    ];

    public function partyName()
    {
       return $this->belongsTo(LedgerModel::class, 'party_id', 'id');
    }

    public function getPartyNameAttribute()
    {
       
        $partyname = LedgerModel::find(id: $this->party_id);
        return $partyname ? $partyname->party_name : 'Unknown'; 
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    public function customer()
    {
        return $this->belongsTo(LedgerCustomerModel::class,'party_customer_id', 'id');
    }

    public function getDiscountTypeIdAttribute()
    {
       
    }
}





