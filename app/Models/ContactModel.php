<?php

namespace App\Models;

use App\Models\BaseModel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactModel extends BaseModel
{
    protected $table = 'contact_info';
    protected $guarded = ['id', 'created_at', 'updated_at'];
   // protected $hidden = [''];

    protected $default = [
        'xid',
        'contact_tittle',
        'contact_number',
        'contact_whatsapp_number',
        'contact_email_id',
        'contact_name',
        'contact_last_name',
        'contact_designation',
        'ledger_id'
      ];
}
