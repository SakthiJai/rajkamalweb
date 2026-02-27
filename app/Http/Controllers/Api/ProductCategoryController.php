<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiBaseController;
use App\Models\ProductCategory;
use App\Http\Requests\Api\ProductCategory\IndexRequest;
use App\Http\Requests\Api\ProductCategory\StoreRequest;
use App\Http\Requests\Api\ProductCategory\UpdateRequest;
use App\Http\Requests\Api\ProductCategory\DeleteRequest;
use Illuminate\Http\Request;


class ProductCategoryController extends ApiBaseController
{
    protected $model = ProductCategory::class;
    protected $indexRequest = IndexRequest::class;
    protected $storeRequest = StoreRequest::class;
    protected $updateRequest = UpdateRequest::class;
    protected $deleteRequest = DeleteRequest::class;

}
