<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\BaseModel;

class Station extends BaseModel
{
    protected $table = 'station';
    protected $default = ['xid','station_name'];

    protected $guarded = ['id',  'created_at', 'updated_at'];

    protected $appends = ['xid'];
    
    protected $filterable = ['id', 'station_name'];

}

