<?php

namespace App\Models;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class LedgerItem extends BaseModel
{
    protected $table = 'ledger_item';
  
    protected $guarded = ['id', 'created_at', 'updated_at'];

    //protected $hidden = ['ledger_id'];
    protected $default = [
        'xid',
        'ledger_id',
        'keyword',
        'value',
      ];

      public function legeritem()
      {
          return $this->belongsTo(LedgerModel::class, 'ledger_id', 'id');
      }
}

