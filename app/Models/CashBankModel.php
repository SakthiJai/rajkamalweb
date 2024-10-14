<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\BaseModel;

class CashBankModel extends BaseModel
{
    use HasFactory;
    protected $table = 'add_bank_accounts';

    protected $default = ['account_name', 'opening_balance', 'as_of_date',
    'bank_account_number', 're_enter_bank_account_number',
    'ifsc_code', 'bank_branch_name', 'account_holder_name', 'upi_id'];

    protected $guarded = ['id', 'created_at', 'updated_at'];
    protected $appends = ['xid'];
    protected $filterable = ['account_name', 'bank_account_number', 'ifsc_code'];
    protected $allowedFilters = [ 'id','account_name','bank_account_number', ];
}
