<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiBaseController;
use App\Http\Requests\Api\Paymentmodes\IndexRequest;
use App\Http\Requests\Api\Paymentmodes\StoreRequest;
use Illuminate\Http\Request;
use App\Models\PaymentModeModel;

class PaymentmodesController extends ApiBaseController
{
    protected $model = PaymentModeModel::class;
    protected $indexRequest = IndexRequest::class;
    protected $storeRequest = StoreRequest::class;
}
