<?php

namespace App\Models;

use App\Models\BaseModel;

class PaymentDueDate extends BaseModel
{
    protected $table = 'payment_due_date';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $casts = [
        'day' => 'integer',
    ];
}
