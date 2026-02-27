<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankDetail extends BaseModel
{
    protected $table = 'bank_details';
    protected $guarded = ['id','ledger_id', 'created_at', 'updated_at'];
    protected $hidden = ['ledger_id'];

    protected $default = [
        'xid',
        'bank_bank_name',
        'bank_account_number',
        'bank_branch',
        'bank_account_type',
        'bank_ifsc_code',
        'bank_account_holder_name',
    ];

    // protected $default = [
    //     'xid',
    //     'bank_name',
    //     'account_number',
    //     'branch',
    //     'account_type',
    //     'ifsc_code',
    //     'account_holder_name',
    // ];




}
