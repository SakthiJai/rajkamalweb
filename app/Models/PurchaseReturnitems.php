<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Casts\Hash;
use App\Models\BaseModel;

class PurchaseReturnitems extends BaseModel
{
    
    protected $table = 'purchase_return_item_details';

    protected $default = ['xid','product_name','stock','pack'];

    protected $guarded = ['id', 'created_at', 'updated_at'];


    protected $hidden = ['purchase_id', 'user_id', 'order_id'];

    protected $appends = ['xid', 'purchase_id', 'x_user_id',  'x_product_id', 'x_unit_id','product_name','stock','pack'];
    protected $filterable = ['id', 'product_id'];


    protected $hashableGetterFunctions = [
        'getXUserIdAttribute' => 'user_id',
        'getXOrderIdAttribute' => 'purchase_id',
        'getXProductIdAttribute' => 'product_id',
        'getXUnitIdAttribute' => 'unit_id',
    ];


    protected $casts = [
        'user_id' => Hash::class . ':hash',
        'purchase_id' => Hash::class . ':hash',
        'product_id' => Hash::class . ':hash',
        'unit_id' => Hash::class . ':hash',
         'free' => Hash::class . ':hash',
        'quantity' => 'double',
        'mrp' => 'double',
        'unit_price' => 'double',
        'single_unit_price' => 'double',
        'tax_rate' => 'double',
        'discount_rate' => 'double',
        'total_tax' => 'double',
        'total_discount' => 'double',
        'subtotal' => 'double',
        'total_sales_price' => 'double',
        'unit_sold' => 'double',
    ];


    protected static function boot()
    {
        parent::boot();
    }
  public function order()
    {
        return $this->belongsTo(PurchaseReturnDetalis::class, 'purchase_id', 'id');
    }

    public function getProductNameAttribute()
     {
        
         $product = Product::find(id: $this->product_id);
         return $product ? $product->name : 'Unknown'; 
     }

     public function getStockAttribute()
     {
        
         $product = Product::find(id: $this->product_id);
         return $product ? $product->stock : 0; 
     }

     public function getMrpAttribute()
     {
        
         $product = Product::find(id: $this->product_id);
         return $product ? $product->mrp : 0; 
     }

     public function getPackAttribute()
     {
        
         $product = Product::find(id: $this->product_id);
         return $product ? $product->packing : 0; 
     }


     public function product()
     {
         return $this->belongsTo(Product::class, 'product_id', 'id');
     }

     public function unit()
     {
         return $this->belongsTo(Unit::class, 'unit_id', 'id');
     }
 
     public function orderItemTaxes()
     {
         return $this->hasMany(OrderItemTax::class, 'order_item_id', 'id');
     }
     public function user()
     {
         return $this->belongsTo(StaffMember::class, 'user_id', 'id')->withoutGlobalScope('type');
     }
 


}
