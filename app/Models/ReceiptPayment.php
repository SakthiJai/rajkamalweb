<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\BaseModel;
use App\Models\PaymentMode;
use App\Models\LedgerModel;

class ReceiptPayment extends BaseModel
{
    //use HasFactory;

    protected $table = 'receipt_payments';

    protected $default = [
       
        'id',
        'receiptpayment_mode',
        'narration',
        'payment_voucher',
        'inst_type',
        'inst_no',
        'station',
        'receipt_date',
        'party_id',
        'reciept_payment',
        'subtotal',
        'short_subtotal'
    ];



    public function partyName()
    {
        return $this->belongsTo(LedgerModel::class, 'party_id', 'id');
    }



    public function getRecieptPaymentAttribute()
    {
        $unit  = PaymentMode::find($this->receiptpayment_mode);
        return $unit ? $unit->name : 'Unknown';
    }
}
