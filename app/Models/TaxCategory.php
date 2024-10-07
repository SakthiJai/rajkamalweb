<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use App\Models\BaseModel;
use App\Scopes\CompanyScope;

class TaxCategory extends BaseModel
{
    protected $table = 'tax_catagories';

    protected $default = ['xid', 'sales_type', 'local', 'central','lgst','cgst','sgst','cess','nature_of_transaction','tax_ability','lgst_ledger','cgst_ledger','sgst_ledger','cess_ledger'];

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $filterable = ['sales_type','local'];

    protected $appends = ['xid'];

    protected $casts = [
        'is_deletable' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new CompanyScope);
    }

}
