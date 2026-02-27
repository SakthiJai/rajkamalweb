<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
 use App\Scopes\CompanyScope;
 use App\Models\BaseModel;

class ReceiptItems extends BaseModel
{
    protected $table = 'receipt_items';

    protected $default = ['xid','receipt_item', 'status',];

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $appends = ['xid'];
    
    protected $filterable = ['id', 'receipt_item'];
}






