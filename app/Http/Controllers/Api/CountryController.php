<?php

namespace App\Http\Controllers\Api;

use App\Models\Country;
use App\Http\Controllers\ApiBaseController;
use App\Http\Requests\Api\Country\IndexRequest;
use App\Http\Requests\Api\Country\StoreRequest;
use App\Http\Requests\Api\Country\UpdateRequest;
use App\Http\Requests\Api\Country\DeleteRequest;
use Illuminate\Http\Request;
class CountryController extends ApiBaseController
{
    protected $model = Country::class;
    protected $indexRequest = IndexRequest::class;
    protected $storeRequest = StoreRequest::class;
    protected $updateRequest = UpdateRequest::class;
    protected $deleteRequest = DeleteRequest::class;
}
