<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiBaseController;
use App\Http\Requests\Api\HSN\IndexRequest;
use App\Http\Requests\Api\HSN\StoreRequest;
use App\Http\Requests\Api\HSN\UpdateRequest;
use App\Http\Requests\Api\HSN\DeleteRequest;
use App\Models\HSN;
use Examyou\RestAPI\Exceptions\ApiException;

class HSNController extends ApiBaseController
{
	protected $model = HSN::class;

	protected $indexRequest = IndexRequest::class;
	protected $storeRequest = StoreRequest::class;
	protected $updateRequest = UpdateRequest::class;
	protected $deleteRequest = DeleteRequest::class;
}
