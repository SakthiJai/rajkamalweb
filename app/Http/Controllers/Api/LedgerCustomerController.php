<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiBaseController;
use Illuminate\Http\Request;
use App\Models\LedgerCustomerModel;

class LedgerCustomerController extends ApiBaseController
{
    protected $model = LedgerCustomerModel::class;
    

}
