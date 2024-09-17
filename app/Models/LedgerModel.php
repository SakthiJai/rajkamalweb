<?php

namespace App\Models;

use App\Models\State;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Station;
use App\Models\Country;

class LedgerModel extends BaseModel
{
    use HasFactory;

    protected $table = 'ledger';
    protected $appends = ['xid','station_name','name','state_name'];
    protected $filterable = ['id','party_name'];
    protected $allowedFilters = [ 'party_name','id' ];
    protected $default = [
        'xid',
        'party_name',
        'station',
        'Address',
        'stock_country',
        'stock_state',
        'stock_city',
        'parent_ledger',
        'account_group',
        'mail_to',
        'stock_pincode',
        'balancing_method',
        'opening_balance',
        'credit_days',
        'phone_number',
        'mobile_number',
        'whatsapp_number',
        'ledger_type',
        'pan_number',
        'customer_title',
        'customer_first_name',
        'customer_last_name',
        'gender',
        'designation',
        'website',
        'customer_email',
        'bank_name',
        'branch',
        'ifsc_code',
        'account_number',
        'account_type',
        'account_holder_name',
        'name',
        'state_name',
        'station_name',
    ];

    public function getStationNameAttribute()
    {
        $station = Station::find($this->station);
        return $station ? $station->station_name : 'Unknown'; 
    }

    public function getNameAttribute()
    {
        $counrty = Country::find($this->stock_country);
        return $counrty ? $counrty->name : 'Unknown'; 
    }

    public function getStateNameAttribute()
    {
        $state = State::find($this->stock_state);
        return $state ? $state->state_name : 'Unknown'; 
    }
    public function bills()
    {
        return $this->hasMany(Order::class);
    }


}
