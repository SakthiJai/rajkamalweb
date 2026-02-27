<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\ApiBaseController;
use Illuminate\Http\Request;
use App\Http\Requests\Api\CashBank\IndexRequest;
use App\Http\Requests\Api\CashBank\StoreRequest;
use App\Http\Requests\Api\CashBank\UpdateRequest;
use App\Http\Requests\Api\CashBank\DeleteRequest;
use App\Models\CashBankModel;

class CashBankController extends ApiBaseController
{
    // Define the model used by this controller
    protected $model = CashBankModel::class;
    // Define the request classes for different actions
    protected $indexRequest = IndexRequest::class;
    protected $storeRequest = StoreRequest::class;
    protected $updateRequest = UpdateRequest::class;
    
}


