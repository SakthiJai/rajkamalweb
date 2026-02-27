<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use App\Models\BaseModel;
use App\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountModel extends BaseModel
{
    protected $table = 'discount_percentage'; 

    protected $default = ['xid','discount_type', 'status',];
    protected $guarded = ['id','xid', 'status', 'created_at', 'updated_at']; 

    protected $filterable = ['discount_type']; 

    protected $appends = ['xid'];
    protected $casts = [
        'status' => 'integer',
    ];
    





}
