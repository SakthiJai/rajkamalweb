<?php

namespace App\Models;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Scopes\CompanyScope;
class Godowns extends BaseModel
{
    use HasFactory;
    protected $table = 'god_owns';
    protected $default = ['xid', 'god_owns_name' ,'status',];

    protected $guarded = ['id', 'is_deletable','created_at', 'updated_at'];

    protected $filterable = ['id', 'god_owns_name'];

    protected $hidden = [];

    protected $appends = ['xid'];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new CompanyScope);
    }

    protected $casts = [
        'is_deletable' => 'integer',
    ];
}
