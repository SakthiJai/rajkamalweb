<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiBaseController;
use App\Http\Requests\Api\ProductCompany\IndexRequest;
use App\Http\Requests\Api\ProductCompany\StoreRequest;
use App\Http\Requests\Api\ProductCompany\UpdateRequest;
use App\Http\Requests\Api\ProductCompany\DeleteRequest;
use App\Models\ProductCompany;
use Examyou\RestAPI\Exceptions\ApiException;

class ProductCompanyController extends ApiBaseController
{
	protected $model = ProductCompany::class;

	protected $indexRequest = IndexRequest::class;
	protected $storeRequest = StoreRequest::class;
	protected $updateRequest = UpdateRequest::class;
	protected $deleteRequest = DeleteRequest::class;
}
