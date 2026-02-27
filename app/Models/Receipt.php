<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\BaseModel;

use App\Models\ReceiptBank;
use App\Models\LedgerModel;

class Receipt extends BaseModel
{
    use HasFactory;

    protected $table = 'receipt_accounttrans';

    protected $default = [
        'id',
        'order_date',
        'receipt_mode',
        'narration',
        'voucher_number',
        'party_id',
        'station',
        'inst_type',
        'inst_no',
        'receipt_date',
        'amount',
        'short_amount',
        'subtotal',
        'short_subtotal',
       

    ];

    public function partyName()
    {
        return $this->belongsTo(LedgerModel::class, 'party_id', 'id');
    }
    public function getReceiptItemAttribute()
{
    $godowns  = ReceiptBank::find($this->receipt_mode);
    return $godowns ? $godowns->bank_name : 'Unknown';
}


}
