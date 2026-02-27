<?php

namespace App\Http\Controllers\Api;

use App\Models\DiscountModel;
use Examyou\RestAPI\ApiResponse;
use App\Http\Controllers\ApiBaseController;
use App\Http\Requests\Api\Discount\IndexRequest;
use App\Http\Requests\Api\Discount\CustomerRequest;
use App\Http\Requests\Api\Discount\DeleteRequest;
use App\Http\Requests\Api\Discount\StoreRequest;
use App\Http\Requests\Api\Discount\UpdateRequest;
use Illuminate\Http\Request;


class DiscountController  extends ApiBaseController
{
    protected $model = DiscountModel::class;
    protected $indexRequest = IndexRequest::class;
    protected $storeRequest = StoreRequest::class;
    protected $updateRequest = UpdateRequest::class;
    protected $deleteRequest = DeleteRequest::class;
}

    