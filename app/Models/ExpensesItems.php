<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\BaseModel;

class ExpensesItems extends BaseModel
{
    use HasFactory;
    protected $table = 'expenses_items';

    protected $default = ['xid','id','order_date','party_id','party_customer_id', 'category_name', 'expense_category','expense_number','original_invoice_number','payment_mode','payment_mode_from','address','category_name'];

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $appends = ['xid','party_name'];

    protected $allowedFilters = [ 'expense_number' ];
    
    protected $filterable = ['id', 'cus_name','expense_number'];

    protected $casts = [
        'total_amount' => 'double',
        'tax_amount' => 'double',
        'total_discount' => 'double',
        'total_items' => 'double',
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


    public function category()
    {
        return $this->belongsTo(Categorys::class, 'category_name', 'id');
    }


    public function customer()
    {
        return $this->belongsTo(LedgerCustomerModel::class,'party_customer_id', 'id');
    }



    public function getCategeroyNameAttribute()
    {
        $station = Categorys::find($this->station);
        return $station ? $station->category_name : 'Unknown'; 
    }

}
