<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiBaseController;
use App\Models\SalesNumber;
use App\Http\Requests\Api\SalesNumber\IndexRequest;
use App\Http\Requests\Api\SalesNumber\StoreRequest;
use App\Http\Requests\Api\SalesNumber\UpdateRequest;
use App\Http\Requests\Api\SalesNumber\DeleteRequest;
class SalesNumberController extends ApiBaseController
{
    protected $model = SalesNumber::class;
    protected $indexRequest = IndexRequest::class;
    protected $storeRequest = StoreRequest::class;
    protected $updateRequest = UpdateRequest::class;
    protected $deleteRequest = DeleteRequest::class;

}
