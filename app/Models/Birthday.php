<?php

namespace App\Models;
use App\Models\BaseModel;
use App\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Birthday extends BaseModel
{
    protected $table = 'birthday';

    protected $default = ['id', 'birthday_date', 'anniversary_date'];
    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $filterable = ['name'];
    
    
    protected static function boot()
    { 
        parent::boot();

        static::addGlobalScope(new CompanyScope);
    }
}
