<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiBaseController;
use App\Http\Requests\Api\ReceiptItems\IndexRequest;
use App\Http\Requests\Api\ReceiptItems\StoreRequest;
use App\Http\Requests\Api\ReceiptItems\UpdateRequest;
use App\Http\Requests\Api\ReceiptItems\DeleteRequest;
 use App\Models\ReceiptItems;
use Examyou\RestAPI\Exceptions\ApiException;

class ReceiptModeController extends ApiBaseController
{
    protected $model = ReceiptItems::class;

    protected $indexRequest = IndexRequest::class;
    protected $storeRequest = StoreRequest::class;
    protected $updateRequest = UpdateRequest::class;
    protected $deleteRequest = DeleteRequest::class;



}
