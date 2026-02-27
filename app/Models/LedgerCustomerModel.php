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

    protected $filterable = ['id', 'ledger_id', '', 'mobile_number', 'cus_name'];
    protected $allowedFilters = ['id', 'ledger_id', 'mobile_number', 'cus_name'];

    protected $appends = ['xid','state_name',];
    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $default = [

        'ledger_id',
        'cus_name',
        'mobile_number',
        'gender',
        'age',
        'billing_discount',
        'customer_city',
        'customer_type',
        'dob',
        'phone_number',
        'pin_number',
        'status',
        'government_id',
        'customer_state',
        'state_name',

    ];


    public function getStateNameAttribute()
    {
        $state = State::find($this->customer_state);
        return $state ? $state->state_name : '-';
    }

      public function state()
    {
        return $this->belongsTo(State::class, 'customer_state', 'id');
    }
    

}
