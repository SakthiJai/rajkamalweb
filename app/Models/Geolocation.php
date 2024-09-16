<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Builder;
use App\Scopes\CompanyScope;
class Geolocation extends BaseModel
{
    protected $table = 'geolocation';

    protected $default = ['id', 'latitude', 'longitude'];
    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $filterable = ['name'];
    protected $appends = ['xid'];

    protected static function boot()
    { 
        parent::boot();

        static::addGlobalScope(new CompanyScope);
    }
}
