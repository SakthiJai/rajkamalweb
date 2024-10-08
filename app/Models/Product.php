<?php

namespace App\Models;

use App\Casts\Hash;
use App\Classes\Common;
use App\Models\TaxCategory;
use App\Models\HSN;
use App\Models\ProductCompany;
use App\Models\BaseModel;
use App\Scopes\CompanyScope;
use Vinkla\Hashids\Facades\Hashids;

class Product extends BaseModel
{
    protected $table = 'products';
    protected $default =[
                        'id',
                        'company_id',
                        'warehouse_id',
                        'product_type',
                        'parent_id',
                        'parent_item_code',
                        'name',
                        'slug',
                        'barcode_symbology',
                        'item_code',
                        'image',
                        'category_id',
                        'brand_id',
                        'unit_id',
                        'description',
                        'user_id',
                        'created_at',
                        'updated_at',
                        'unit_1st',
                        'unit_in_decimal',
                        'hsn_sac',
                        'tax_category',
                        'company',
                        'mrp',
                        'purchase_rate',
                        'cost',
                        'sale_rate',
                        'rate_b',
                        'free_scheme_1',
                        'free_scheme_2',
                        'scheme_type',
                        'status',
                        'color_type',
                        'rate_d',
                        'rate_f',
                        'discount',
                        'item_disc_1_percent',
                        'volume_disc_1',
                        'max_disc_percent',
                        'min_quantity',
                        'max_quantity',
                        'reorder_days',
                        'reorder_qty',
                        'prohibited',
                        'visibility',
                        'mfr_name',
                        'upload_image',
                        'packing',
                        'sales_rate',
                        'cgst',
                        'lgst',
                        'sgst',
                        'company_name',
                        'unit_name',
                        'hsn'
                    ];


    protected $hidden = ['category_id', 'brand_id', 'unit_id', 'user_id', 'warehouse_id', 'variant_id', 'variant_value_id', 'parent_id'];

    protected $appends = ['xid','cgst','hsn','lgst','sgst','company_name','unit_name','x_category_id', 'x_brand_id', 'x_unit_id', 'x_user_id', 'x_warehouse_id', 'x_variant_id', 'x_variant_value_id', 'x_parent_id', 'image_url'];

    protected $filterable = ['id', 'products.id', 'name', 'item_code', 'category_id', 'brand_id'];

    protected $hashableGetterFunctions = [
        'getXCategoryIdAttribute' => 'category_id',
        'getXBrandIdAttribute' => 'brand_id',
        'getXUnitIdAttribute' => 'unit_id',
        'getXUserIdAttribute' => 'user_id',
        'getXWarehouseIdAttribute' => 'warehouse_id',
        'getXVariantIdAttribute' => 'variant_id',
        'getXVariantValueIdAttribute' => 'variant_value_id',
        'getXParentIdAttribute' => 'parent_id',
    ];

    protected $casts = [
        'category_id' => Hash::class . ':hash',
        'brand_id' => Hash::class . ':hash',
        'unit_id' => Hash::class . ':hash',
        'user_id' => Hash::class . ':hash',
        'warehouse_id' => Hash::class . ':hash',
        'variant_id' => Hash::class . ':hash',
        'variant_value_id' => Hash::class . ':hash',
        'parent_id' => Hash::class . ':hash',
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new CompanyScope);
    }

    public function getImageUrlAttribute()
    {
        $productImagePath = Common::getFolderPath('productImagePath');

        return $this->image == null ? asset('images/product.png') : Common::getFileUrl($productImagePath, $this->image);
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id', 'id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(StaffMember::class, 'user_id', 'id');
    }

    public function warehouseStocks()
    {
        return $this->hasMany(WarehouseStock::class, 'product_id', 'id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'product_id', 'id');
    }

    public function productVariations()
    {
        return $this->hasMany(ProductVariant::class, 'product_id', 'id');
    }

    public function variations()
    {
        return $this->hasMany(Product::class, 'parent_id', 'id');
    }

    public function variation()
    {
        return $this->belongsTo(Variation::class, 'variant_id', 'id');
    }

    public function variationType()
    {
        return $this->belongsTo(Variation::class, 'variant_value_id', 'id');
    }

    public function stockHistory()
    {
        return $this->hasMany(StockHistory::class, 'product_id', 'id');
    }

    public function customFields()
    {
        return $this->hasMany(ProductCustomField::class, 'product_id', 'id');
    }

    public function details()
    {
        return $this->belongsTo(ProductDetails::class, 'id', 'product_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function getCgstAttribute()
    {
        $taxCategory  = TaxCategory::find($this->tax_category);
        return $taxCategory ? $taxCategory->cgst : 'Unknown';
    }

    public function getLgstAttribute()
    {
        $taxCategory  = TaxCategory::find($this->tax_category);
        return $taxCategory ? $taxCategory->lgst : 'Unknown';
    }
    public function getSgstAttribute()
    {
        $taxCategory  = TaxCategory::find($this->tax_category);
        return $taxCategory ? $taxCategory->sgst : 'Unknown';
    }

    public function getCompanyNameAttribute()
    {
        $company  = ProductCompany::find($this->company_id);
        return $company ? $company->name : 'Unknown';
    }

    public function getUnitNameAttribute()
    {
        $unit  = Unit::find($this->unit_1st);
        return $unit ? $unit->name : 'Unknown';
    }

    public function getHsnAttribute()
    {
        $unit  = HSN::find($this->hsn_sac);
        return $unit ? $unit->code : 'Unknown';
    }

}
