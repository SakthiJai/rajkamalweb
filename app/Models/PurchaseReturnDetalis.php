<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\BaseModel;
use App\Casts\Hash;

class PurchaseReturnDetalis extends BaseModel
{
   
    protected $table = 'purchase_return_detalis';



    protected $default = ['xid','id','dr_number','order_id','party_id','party_customer_id','order_date', 'description','return_by','party_name'];

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $appends = ['xid','party_name'];
    
    protected $allowedFilters = [ 'dr_number','order_id' ];
    
    protected $filterable = ['id', 'cus_name','dr_number','order_id'];

    protected $casts = [
        'order_date' => 'datetime',
        'total_amount' => 'double',
        'tax_amount' => 'double',
        'total_discount' => 'double',
        'total_items' => 'double',
        'total_quantity' => 'double',
        'total_free' => 'double',
        'dr_number' => 'string',
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
}
