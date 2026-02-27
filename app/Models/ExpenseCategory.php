<?php

namespace App\Models;

use App\Models\BaseModel;
 use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class ExpenseCategory extends BaseModel
{
    protected $table = 'expense_categories';

    protected $default = ['xid', 'name', 'description'];

    protected $guarded = ['id', 'created_at', 'updated_at'];

    //protected $hidden = ['id'];

    protected $appends = ['xid'];

    protected $filterable = ['name','description'];


}

