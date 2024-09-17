<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiBaseController;
use App\Http\Requests\Api\SalesNumber\IndexRequest;
use App\Http\Requests\Api\SalesNumber\StoreRequest;
use App\Http\Requests\Api\SalesNumber\UpdateRequest;
use App\Http\Requests\Api\SalesNumber\DeleteRequest;
use App\Models\Order;
use App\Traits\OrderTraits;

class SalesNumberController extends ApiBaseController
{
	use OrderTraits;

	protected $model = Order::class;

	protected $indexRequest = IndexRequest::class;
	protected $storeRequest = StoreRequest::class;
	protected $updateRequest = UpdateRequest::class;
	protected $deleteRequest = DeleteRequest::class;

	public function __construct()
	{
		parent::__construct();

		$this->orderType = "sales";
	}
}
