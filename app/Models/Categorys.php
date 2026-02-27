<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\BaseModel;

class Categorys extends BaseModel
{
    use HasFactory;

    protected $table = 'category_list'; 

    protected $default = ['xid','category_name	', 'status',];

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $appends = ['xid'];
    
    protected $filterable = ['id', 'category_name',];
}
