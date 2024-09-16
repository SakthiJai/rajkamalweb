<?php

namespace App\Models;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends BaseModel
{
    use HasFactory;

    protected $table = 'countries';

    protected $default = ['xid','name', 'status',];

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $appends = ['xid'];
    
    protected $filterable = ['id', 'name'];
}
