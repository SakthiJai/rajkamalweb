<?php

namespace App\Models;

use App\Models\State;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Station;
use App\Models\Country;
use App\Models\Categorys;

class LedgerModel extends BaseModel
{
    use HasFactory;

    protected $table = 'ledger';
    protected $appends = ['xid','station_name','name','country_name','state_name','party_name'];
    protected $filterable = ['id','party_name','phone_number',
        'mobile_number',
        'whatsapp_number'];
    protected $allowedFilters = [ 'id','party_name','mobile_number'];

    protected $default = [
        'xid',
        'id',
        'party_name','party_full_name',
        'party_type',
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
        'credit_type',
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
        "ship_address",
        "ship_city",
        "ship_pincode",
        "ship_contactno",
         'name',
        'state_name',
        'station_name',
        'gst_number',
        'debit',
        'credit',
        'credit_limit',
        'with_gst_number',
        'with_pan_number',
        'contact_mobile',
        'country_name',
        'category',
        'trade_name',
    ];
    protected $casts = [
        'ledger_id' => 'integer',
        // Make sure these attributes exist in your database schema
        'keyword' => 'string',
        'value' => 'string',
        'is_active' => 'boolean',
    ];





    public function getNameAttribute()
    {
        return isset($this->attributes['id']) ? $this->attributes['id'] : null;
    }
    
    public function getPartyNameAttribute()
    {
        return isset($this->attributes['party_name']) ? $this->attributes['party_name'] : 
               (isset($this->attributes['party_full_name']) ? $this->attributes['party_full_name'] : "");
    }


    public function getStationNameAttribute()
    {
        if (empty($this->station) || empty($this->stock_country)) {
            return 'Unknown';
        }
        $counrty = Country::find($this->stock_country);
        return $counrty ? $counrty->country_name : 'Unknown';
    }
    // public function getPartyFullNameAttribute()
    // {
    //     $station = LedgerModel::find($this->station);
    //     $counrty = Country::find($this->parent_ledger);
    //     return $counrty ? $counrty->party_full_name : 'Unknown';
    // }

    public function getCountryNameAttribute()
    {
        if (empty($this->stock_country)) {
            return 'Unknown';
        }
        $counrty = Country::find($this->stock_country);
        return $counrty ? $counrty->country_name: 'Unknown';
    }

    public function getCategoryNameAttribute()
    {
        if (empty($this->category)) {
            return 'Unknown';
        }
        $counrty = Categorys::find($this->category);
        return $counrty ? $counrty->category_name	: 'Unknown';
    }

    public function getStateNameAttribute()
    {
        if (empty($this->stock_state)) {
            return 'Unknown';
        }
        $state = State::find($this->stock_state);
        return $state ? $state->state_name : 'Unknown';
    }
    public function bills()
    {
        return $this->hasMany(Order::class);
    }
    public function items()
    {
        return $this->hasMany(LedgerItem::class, 'ledger_id', localKey: 'id');
    }
    public function contactinfo()
    {
        return $this->hasMany(ContactModel::class, 'ledger_id', localKey: 'id');
    }



}
