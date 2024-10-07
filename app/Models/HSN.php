<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use App\Models\BaseModel;
use App\Scopes\CompanyScope;

class HSN extends BaseModel
{
    protected $table = 'hsc_sac';

    protected $default = ['xid', 'code', 'name', 'status'];

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $filterable = ['name','code'];

    protected $hidden = [];

    protected $appends = ['xid'];

}
