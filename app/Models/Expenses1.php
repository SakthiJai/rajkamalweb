<?php

namespace App\Models;

use App\Casts\Hash;
use App\Classes\Common;
use App\Models\BaseModel;
use App\Scopes\CompanyScope;
use Vinkla\Hashids\Facades\Hashids;

class Expenses1 extends BaseModel
{
    protected $table = 'expenses1';

    protected $default = ['xid'];

    protected $dates = ['order_date','expense_date'];

    protected $guarded = ['id', 'created_at', 'updated_at'];

    //protected $hidden = ['id', 'party_id', 'expense_category_id'];

    protected $appends = ['xid','image_url','x_party_id', 'x_expense_category_id','x_payment_mode_id','x_user_id','x_modified_user_id'];

    protected $filterable = ['party_id', 'expense_category_id','user_id','modified_user_id' ,'expense_category'];

    protected $hashableGetterFunctions = [
        'getXPaymentModeIdAttribute' => 'payment_mode_id',
        'getXPartyIdAttribute' => 'party_id',
        'getXExpenseCategoryIdAttribute' => 'expense_category_id',
        'getXUserIdAttribute' => 'user_id',
        'getXModifiedUserIdAttribute' => 'modified_user_id',
    ];

   /* protected $casts = [
        'date' => 'datetime',
        'warehouse_id' => Hash::class . ':hash',
        'user_id' => Hash::class . ':hash',
        'expense_category_id' => Hash::class . ':hash',
        'amount' => 'double',
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new CompanyScope);
    }

    public function getBillUrlAttribute()
    {
        $expenseBillPath = Common::getFolderPath('expenseBillPath');

        return $this->bill == null ? null : Common::getFileUrl($expenseBillPath, $this->bill);
    }*/

    // public static function getFolderPath($key) {
    //     $paths = [
    //          'invoicesPath' => 'invoices/',
    //         'expensesPath' => 'expenses/'
    //     ];

    //     return $paths[$key] ?? null;
    // }


    public function expenseCategory()
    {
        return $this->hasOne(ExpenseCategory::class, 'id', 'expense_category_id');
    }

    public function party()
    {
       return $this->hasOne(LedgerModel::class, 'id', 'party_id');
    }

    public function user()
    {
        return $this->belongsTo(StaffMember::class, 'user_id', 'id')->withoutGlobalScope('type');
    }

    public function modified_user()
    {
        return $this->belongsTo(StaffMember::class, 'modified_user_id', 'id')->withoutGlobalScope('type');
    }

    public function paymentModes()
    {
       return $this->hasOne(PaymentMode::class, 'id', 'payment_mode_id');
    }
    public function getImageUrlAttribute()
    {
        // $categoryLogoPath = Common::getFolderPath('expensesPath');

       // return $this->image == null ? asset('images/category.png') : Common::getFileUrl($categoryLogoPath, $this->image);
    }

   /* public function user()
    {
        return $this->hasOne(StaffMember::class, 'id', 'user_id');
    }

    public function warehouse()
    {
        return $this->hasOne(Warehouse::class, 'id', 'warehouse_id');
    }*/
}
