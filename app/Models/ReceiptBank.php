<?php

namespace App\Models;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReceiptBank extends BaseModel
{
    use HasFactory;
    protected $table = 'receipt_bank_account';

    protected $default = ['xid', 'bank_name', 'accountant_name', 'ifsc_code', 'account_number', 'status'];

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $appends = ['xid'];

    protected $filterable = ['id', 'bank_name', 'accountant_name', 'ifsc_code', 'account_number'];



}
