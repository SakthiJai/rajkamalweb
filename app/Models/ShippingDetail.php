<?php

namespace App\Models;

use App\Models\BaseModel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingDetail extends BaseModel
{

    protected $table = 'shipping_details';
    protected $guarded = ['id', 'ledger_id', 'created_at', 'updated_at'];
    protected $appends = ['xid'];

    protected $default = [
        'xid',
        'shipping_address',
        'shipping_city',
        'shipping_pincode',
        'is_temporary',
        'shipping_contact_number',
        'ledger_id'
    ];
    protected $allowedFilters = ['id', 'shipping_address','shipping_city','shipping_pincode','shipping_contact_number' ];
    
    //protected $filterable = ['id','shipping_address','shipping_city','shipping_pincode','shipping_contact_number'];

 protected $filterable = ['id','shipping_address','shipping_city','shipping_pincode','shipping_contact_number'];




}
