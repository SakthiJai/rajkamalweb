<?php

namespace App\Http\Controllers\Api;
use App\Models\Godowns;
use App\Http\Controllers\ApiBaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Godowns\IndexRequest;
use App\Http\Requests\Api\Godowns\StoreRequest;
use App\Http\Requests\Api\Godowns\UpdateRequest;
use App\Http\Requests\Api\Godowns\DeleteRequest;
use Illuminate\Http\Request;
class GodownsController extends ApiBaseController
{
    protected $model = Godowns::class;
    protected $indexRequest = IndexRequest::class;
    protected $storeRequest = StoreRequest::class;
    protected $updateRequest = UpdateRequest::class;
    protected $deleteRequest = DeleteRequest::class;

}
 