<?php

namespace App\Models;

use App\Casts\Hash;
use App\Models\BaseModel;
use App\Scopes\CompanyScope;
use Vinkla\Hashids\Facades\Hashids;

class SalesReturnItems extends BaseModel
{
        public function getCurrentStockAttribute()
    {
        // If return_qty is empty, show current stock from ProductDetails
        if (empty($this->return_qty)) {
            $productDetails = \App\Models\ProductDetails::where('product_id', $this->product_id)->first();
            return $productDetails ? $productDetails->current_stock : 0;
        }
        return null;
    }
    // Mutators for saving cgst, sgst, igst, cess from payload
    public function setCgstAttribute($value)
    {
        $this->attributes['cgst'] = $value;
    }

    public function setSgstAttribute($value)
    {
        $this->attributes['sgst'] = $value;
    }

    public function setIgstAttribute($value)
    {
        $this->attributes['igst'] = $value;
    }

    public function setCessAttribute($value)
    {
        $this->attributes['cess'] = $value;
    }
    protected $table = 'sales_return_items';

    protected $default = ['xid','product_name','stock','pack','cgst','sgst'];

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $hidden = ['order_id', 'user_id', 'order_id',  'unit_id', 'tax_id'];

    protected $appends = ['xid', 'x_order_id', 'x_user_id', 'x_order_id', 'x_product_id', 'x_unit_id', 'x_tax_id','product_name','stock','pack','cgst','sgst'];

    protected $filterable = ['id', 'product_id'];


    protected $hashableGetterFunctions = [
        'getXUserIdAttribute' => 'user_id',
        'getXOrderIdAttribute' => 'order_id',
        'getXProductIdAttribute' => 'product_id',
        'getXUnitIdAttribute' => 'unit_id',
        'getXTaxIdAttribute' => 'tax_id',
    ];

    protected $casts = [
        'user_id' => Hash::class . ':hash',
        'order_id' => Hash::class . ':hash',
        'product_id' => Hash::class . ':hash',
        'unit_id' => Hash::class . ':hash',
        'tax_id' => Hash::class . ':hash',
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
    public function getCgstAttribute()
    {
        return $this->attributes['cgst'] ?? 0;
    }

    public function getSgstAttribute()
    {
        return $this->attributes['sgst'] ?? 0;
    }
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id', 'id');
    }

    public function orderItemTaxes()
    {
        return $this->hasMany(OrderItemTax::class, 'order_item_id', 'id');
    }

    public function toArray()
    {
        $array = parent::toArray();
        $array['cgst'] = $this->cgst;
        $array['sgst'] = $this->sgst;
        $array['igst'] = $this->igst;
        $array['cess'] = $this->cess;
        $array['current_stock'] = $this->current_stock;
        return $array;
    }
}
