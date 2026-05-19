<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\BaseModel;
use App\Models\Order;

class PaymentModeModel extends BaseModel
{
    protected $table = 'payment_mode';
    protected $guarded = ['id', 'bill_amount', 'created_at', 'updated_at'];
    protected $default = ['xid','bill_amount', 'order_id','balance_adjusted','amount','settlement_mode','remarks','cash_tender','cash_return','bill_number','payment_reference','payment_type','payment_added_by','payment_added_at','login_user_id','updated_at'];
    protected $hidden = ['created_at', 'pivot'];

    protected $appends = ['xid'];
    
    protected $filterable = [
        'id',
        'bill_amount',
        'bill_number',
        'login_user_id',
        'payment_mode.id',
        'payment_mode.bill_amount',
        'payment_mode.bill_number',
        'payment_mode.login_user_id',
    ];


    // public function getStationNameAttribute()
    // {
    //     $subtotal = Order::find($this->station);
    //     return $subtotal ? $subtotal->subtotal : 'Unknown'; 
    // }

}
