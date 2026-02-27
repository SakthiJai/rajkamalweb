<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\BaseModel;

class SalesReturn extends BaseModel
{
    use HasFactory;

    protected $table = 'sales_return';

    protected $default = ['xid','id','cr_number','order_id','party_id','party_customer_id','order_date', 'description','return_by','party_name'];

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $appends = ['xid','party_name'];

    protected $allowedFilters = [ 'cr_number','order_id' ];

    protected $filterable = ['id', 'cus_name','cr_number','order_id'];

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
    public function customer()
    {
        return $this->belongsTo(LedgerCustomerModel::class,'party_customer_id', 'id');
    }
    public function items()
    {
        return $this->hasMany(SalesReturnItems::class,'order_id', 'id');
    }
}
