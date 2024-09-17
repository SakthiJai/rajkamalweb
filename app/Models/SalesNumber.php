<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\BaseModel;
class SalesNumber extends BaseModel
{
    use HasFactory;

    protected $table = 'party_customers_details';

    protected $default = ['xid','name','ledger_id','mobile_number','gender','age', 'billing_discount','customer_type','customer_status',];

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $appends = ['xid'];
    
    protected $allowedFilters = [ 'name','id' ];
    
    protected $filterable = ['id', 'name'];
}
