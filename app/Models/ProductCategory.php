<?php

namespace App\Models;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategory extends BaseModel
{
    protected $table = 'product_category';
    protected $default = ['xid', 'product_category_name'];

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $filterable = ['product_category_name'];

    protected $hidden = [];

    protected $appends = ['xid'];

   
  }

   

