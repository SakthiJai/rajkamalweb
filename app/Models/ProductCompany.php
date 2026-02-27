<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use App\Models\BaseModel;
use App\Scopes\CompanyScope;

class ProductCompany extends BaseModel
{
    protected $table = 'product_company';

    protected $default = [
        'xid',
        'name',
        'print_remark',
        'status',
        'reorder_perference',
        'store_room',
        'prohibit',
        'invoice_printing',
        'dump_days',
        'reorder_formula',
        'minimum_margin'
    ];

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $filterable = ['name'];

    protected $hidden = [];

    protected $appends = ['xid'];
}
