<?php

namespace App\Http\Controllers\Api;
use App\Models\Paymentsitems;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiBaseController;
use Illuminate\Http\Request;
use App\Http\Requests\Api\Paymentsitems\IndexRequest;
use App\Http\Requests\Api\Paymentsitems\StoreRequest;
use App\Http\Requests\Api\Paymentsitems\UpdateRequest;
use App\Http\Requests\Api\Paymentsitems\DeleteRequest;
class PaymentItemController extends ApiBaseController
{
    protected $model = Paymentsitems::class;

    protected $indexRequest = IndexRequest::class;

    protected $storeRequest = StoreRequest::class;
    protected $updateRequest = UpdateRequest::class;
    protected $deleteRequest = DeleteRequest::class;
}
