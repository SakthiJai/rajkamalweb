<?php

namespace App\Models;

use App\Models\State;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Station;
use App\Models\Country;

class LedgerCustomerModel extends BaseModel
{
    use HasFactory;

    protected $table = 'party_customers_details';
    
    protected $filterable = ['id','ledger_id','name','mobile_number'];
    protected $allowedFilters = [ 'id','ledger_id','mobile_number' ];
    
    protected $default = [
        'xid',
        'ledger_id',
        'cus_name',
        'mobile_number',
        'gender',
        'age',
        'billing_discount',
        'customer_type',
        'status',
    ];

    public function getNameAttribute()
    {
       //print_r($this['name']);
        //$counrty = Country::find(id: $this->stock_country);
        return $this->attributes['name']; 
    }





}
