<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use App\Models\BaseModel;
use App\Scopes\CompanyScope;

class HSN extends BaseModel
{
    protected $table = 'hsc_sac';

    protected $default = ['xid', 'code', 'name', 'status','gst','cgst','sgst','lgst','cess'];

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $filterable = ['name','code','gst','cgst','sgst','lgst'];

    protected $hidden = [];

    protected $appends = ['xid'];

}
