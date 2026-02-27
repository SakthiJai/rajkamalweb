<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use App\Models\BaseModel;
use App\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnReasonModel extends BaseModel
{
    protected $table = 'return_reason'; 

    protected $default = ['xid','return_reason', 'status',];
    protected $guarded = ['id','xid', 'status', 'created_at', 'updated_at']; 

    protected $filterable = ['return_reason']; 

    protected $appends = ['xid'];
    protected $casts = [
        'status' => 'integer',
    ];
    





}
