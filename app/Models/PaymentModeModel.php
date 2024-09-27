<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\BaseModel;
use App\Models\Order;

class PaymentModeModel extends BaseModel
{
    protected $table = 'payment_mode';
    protected $guarded = ['id',  'created_at', 'updated_at'];
    protected $default = ['xid','bill_amount', 'order_id','balance_adjusted','amount','settlement_mode','remarks','cash_tender','cash_return'];

    protected $appends = ['xid'];
    
    protected $filterable = ['id','bill_amount'];


    // public function getStationNameAttribute()
    // {
    //     $subtotal = Order::find($this->station);
    //     return $subtotal ? $subtotal->subtotal : 'Unknown'; 
    // }

}
