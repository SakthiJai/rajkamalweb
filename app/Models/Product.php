<?php

namespace App\Models;

use App\Casts\Hash;
use App\Classes\Common;
use App\Models\TaxCategory;
use App\Models\HSN;
use App\Models\ProductCompany;
use App\Models\ProductCategory;
use App\Models\Godowns;
use App\Models\BaseModel;
use App\Models\Brand;
use App\Scopes\CompanyScope;
use Vinkla\Hashids\Facades\Hashids;

class Product extends BaseModel
{
    protected $table = 'products';
    protected static $hsnCache = [];
    protected static $taxCategoryCache = [];
    protected static $companyCache = [];
    protected static $unitCache = [];

    protected $default =[
                        'xid',
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
                        'gst',
                        'cess',
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

                        'god_owns',
                        'opening_stock',
                        'as_on_date',
                        'category_field',
                        'low_stock',
                        'god_owns_name',
                        'product_category_name',
                        'code',
                        'sales_type',
                        'brand_name',
                        'product_id',
                        'short_code',
                        'image_url',
                        'manufacturer',



                    ];


    protected $hidden = ['category_id', 'brand_id', 'unit_id', 'user_id', 'warehouse_id', 'variant_id', 'variant_value_id', 'parent_id',];

    protected $appends = ['xid','cgst','hsn','lgst','cess','sgst','company_name','sales_type','unit_name','god_owns_name','product_category_name','code','x_category_id', 'x_brand_id', 'x_unit_id', 'x_user_id', 'x_warehouse_id', 'x_variant_id', 'x_variant_value_id', 'x_parent_id', 'image_url'];

    protected $filterable = ['id', 'products.id', 'name', 'item_code', 'category_id', 'brand_id',];

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
        'god_owns_id' => Hash::class . ':hash',
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
    public function taxCategory()
    {
        return $this->belongsTo(TaxCategory::class, 'tax_category', ownerKey: 'id');
    }
    public function hsn()
    {
        return $this->belongsTo(HSN::class, 'hsn_sac', ownerKey: 'id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function getCgstAttribute()
    {
        $hsn = $this->getCachedHsn();
        return $hsn ? $hsn->cgst : 'Unknown';
    }

    public function getLgstAttribute()
    {
        $hsn = $this->getCachedHsn();
        return $hsn ? $hsn->lgst : 'Unknown';
    }
    public function getSgstAttribute()
    {
        $hsn = $this->getCachedHsn();
        return $hsn ? $hsn->sgst : 'Unknown';
    }
    public function getCessAttribute()
    {
        $hsn = $this->getCachedHsn();
        return $hsn ? $hsn->cess : 0;
    }

    public function getSalesTypeAttribute()
    {
        $taxCategory = $this->getCachedTaxCategory();
        return $taxCategory ? $taxCategory->sales_type : 'Unknown';
    }


    public function getCompanyNameAttribute()
    {
        $company = $this->getCachedCompany();
        return $company ? $company->name : 'Unknown';
    }

    public function getUnitNameAttribute()
    {
        $unit = $this->getCachedUnit();
        return $unit ? $unit->name : 'Unknown';
    }

    protected function getCachedHsn()
    {
        $hsnId = $this->hsn_sac;

        if (!$hsnId) {
            return null;
        }

        if (!array_key_exists($hsnId, static::$hsnCache)) {
            static::$hsnCache[$hsnId] = HSN::find($hsnId);
        }

        return static::$hsnCache[$hsnId];
    }

    protected function getCachedTaxCategory()
    {
        $taxCategoryId = $this->tax_category;

        if (!$taxCategoryId) {
            return null;
        }

        if (!array_key_exists($taxCategoryId, static::$taxCategoryCache)) {
            static::$taxCategoryCache[$taxCategoryId] = TaxCategory::find($taxCategoryId);
        }

        return static::$taxCategoryCache[$taxCategoryId];
    }

    protected function getCachedCompany()
    {
        $companyId = $this->company_id;

        if (!$companyId) {
            return null;
        }

        if (!array_key_exists($companyId, static::$companyCache)) {
            static::$companyCache[$companyId] = ProductCompany::find($companyId);
        }

        return static::$companyCache[$companyId];
    }

    protected function getCachedUnit()
    {
        $unitId = $this->unit_1st;

        if (!$unitId) {
            return null;
        }

        if (!array_key_exists($unitId, static::$unitCache)) {
            static::$unitCache[$unitId] = Unit::find($unitId);
        }

        return static::$unitCache[$unitId];
    }

    public function getHsnAttribute()
    {
        $hsn = $this->getCachedHsn();
        return $hsn ? $hsn->code : 'Unknown';
    }

    public function getCodeAttribute()
    {
        $hsn = $this->getCachedHsn();
        return $hsn ? $hsn->code : 'Unknown';
    }

    public function getbrandnameAttribute()
    {
        $unit  = Brand::find($this->brand_name);
        return $unit ? $unit->brands_name : 'Unknown';
    }



public function getProductCategoryNameAttribute()
{
    $unit  = ProductCategory::find($this->category_field);
    return $unit ? $unit->product_category_name : 'Unknown';
}

public function getGodOwnsNameAttribute()
{
    $godowns  = Godowns::find($this->god_owns);
    return $godowns ? $godowns->god_owns_name : 'Unknown';
}


}
