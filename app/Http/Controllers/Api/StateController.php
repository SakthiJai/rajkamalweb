<?php

namespace App\Http\Controllers\Api;

use App\Models\State;
use App\Http\Controllers\ApiBaseController;
use App\Http\Requests\Api\State\IndexRequest;
use App\Http\Requests\Api\State\StoreRequest;
use App\Http\Requests\Api\State\UpdateRequest;
use App\Http\Requests\Api\State\DeleteRequest;
use Illuminate\Http\Request;
class StateController extends ApiBaseController
{
    protected $model = State::class;
    protected $indexRequest = IndexRequest::class;
    protected $storeRequest = StoreRequest::class;
    protected $updateRequest = UpdateRequest::class;
    protected $deleteRequest = DeleteRequest::class;
}
