<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiBaseController;
use App\Models\Categorys;
use App\Http\Requests\Api\Categorys\IndexRequest;
use App\Http\Requests\Api\Categorys\StoreRequest;
use App\Http\Requests\Api\Categorys\UpdateRequest;
use App\Http\Requests\Api\Categorys\DeleteRequest;

class CategorysController extends ApiBaseController
{
    protected $model = Categorys::class;
    protected $indexRequest = IndexRequest::class;
    protected $storeRequest = StoreRequest::class;
    protected $updateRequest = UpdateRequest::class;

    protected $deleteRequest = DeleteRequest::class;

}
