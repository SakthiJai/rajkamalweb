<?php

namespace App\Models;

use App\Casts\Hash;
use App\Scopes\CompanyScope;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\BaseModel;


class PurchaseBillItemsDetail extends BaseModel
{
    protected $table = 'purchase_bill_items_details';

    protected $default = ['xid','product_name','stock','pack','cgst','sgst','discount_rate'];

    protected $guarded = ['id', 'created_at', 'updated_at'];


    protected $hidden = ['purchase_id', 'user_id', 'order_id'];

    protected $appends = ['xid', 'purchase_id', 'x_user_id',  'x_product_id', 'x_unit_id','product_name','stock','pack','cgst','sgst'];
    protected $filterable = ['id', 'product_id'];

    protected $hashableGetterFunctions = [
        'getXUserIdAttribute' => 'user_id',
        'getXOrderIdAttribute' => 'purchase_id',
        'getXProductIdAttribute' => 'product_id',
        'getXUnitIdAttribute' => 'unit_id',
         'getDiscountTypeIdAttribute'=>'discount_type_id'
    ];

    protected $casts = [
        'user_id' => Hash::class . ':hash',
        'purchase_id' => Hash::class . ':hash',
        'product_id' => Hash::class . ':hash',
        'unit_id' => Hash::class . ':hash',
        'free' => 'double',
        'quantity' => 'double',
        'mrp' => 'double',
        'unit_price' => 'double',
        'single_unit_price' => 'double',
        'tax_rate' => 'double',
        'discount_type_id' => 'integer',
        'total_tax' => 'double',
        'total_discount' => 'double',
        'subtotal' => 'double',
        'total_sales_price' => 'double',
        'unit_sold' => 'double',
        'freeqty'=>'double',
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

    public function getCgstAttribute()
    {
       //print_r($this->product);
       // $product = Product::find(id: $this->product_id);

        return $this->product->hsn  ;
    }
    public function getSgstAttribute()
    {

       // $product = Product::find(id: $this->product_id);

        return $this->product->hsn;
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

    public function getDiscountTypeIdAttribute()
    {
       // print_r($this);
        return $this->attributes['discount_type_id'];;
       // return $this->discount_type_id;
    }

}
