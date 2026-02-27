<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\BaseModel;

class ReceiptPaymentItems extends BaseModel
{
    use HasFactory;

    protected $table = 'receiptpayment_items';
    protected $default = ['xid','id','receiptpayment_modes','narrations','order_date'];
    protected $guarded = ['id', 'created_at', 'updated_at'];
    protected $appends = ['xid','receiptpayment_modes'];

    protected $allowedFilters = [ 'receiptpayment_modes','narrations' ];
    
    protected $filterable = ['id','xid', 'narrations','receiptpayment_modes',];
}
