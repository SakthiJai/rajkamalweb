<?php
namespace App\Models;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class State extends BaseModel
{
    protected $table = 'states';
    protected $default = ['xid','country_id','state_name', 'status',];

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $appends = ['xid'];
    
    protected $filterable = ['id', 'state_name','country_id'];
}
