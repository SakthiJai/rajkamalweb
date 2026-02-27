<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\ReceiptBank;
use App\Http\Controllers\ApiBaseController;
use App\Http\Requests\Api\ReceiptBank\IndexRequest;
use App\Http\Requests\Api\ReceiptBank\StoreRequest;
use App\Http\Requests\Api\ReceiptBank\UpdateRequest;
use App\Http\Requests\Api\ReceiptBank\DeleteRequest;
 class ReceiptBankController extends ApiBaseController
{
    protected $model = ReceiptBank::class;
    protected $indexRequest = IndexRequest::class;
    protected $storeRequest = StoreRequest::class;
    protected $updateRequest = UpdateRequest::class;
    protected $deleteRequest = DeleteRequest::class;
}
