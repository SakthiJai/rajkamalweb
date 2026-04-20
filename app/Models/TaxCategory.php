<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use App\Models\BaseModel;
use App\Scopes\CompanyScope;

class TaxCategory extends BaseModel
{
    protected $table = 'tax_catagories';

    protected $default = ['xid', 'sales_type', 'local', 'central','cgst','sgst','cess','nature_of_transaction','tax_ability','cgst_ledger','sgst_ledger','cess_ledger'];

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $filterable = ['sales_type','local'];

    protected $appends = ['xid', 'igst', 'igst_ledger'];

    protected $casts = [
        'is_deletable' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new CompanyScope);
    }
    public function getIgstAttribute()
{
    return $this->lgst;
}

public function getIgstLedgerAttribute()
{
    return $this->lgst_ledger;
}

}
