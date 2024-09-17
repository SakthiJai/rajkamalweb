<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiBaseController;
use App\Http\Requests\Api\TaxCategory\IndexRequest;
use App\Http\Requests\Api\TaxCategory\StoreRequest;
use App\Http\Requests\Api\TaxCategory\UpdateRequest;
use App\Http\Requests\Api\TaxCategory\DeleteRequest;
use App\Models\TaxCategory;
use Examyou\RestAPI\Exceptions\ApiException;

class TaxCategoryController extends ApiBaseController
{
	protected $model = TaxCategory::class;

	protected $indexRequest = IndexRequest::class;
	protected $storeRequest = StoreRequest::class;
	protected $updateRequest = UpdateRequest::class;
	protected $deleteRequest = DeleteRequest::class;
}
