<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiBaseController;
use App\Models\LedgerItem;
use App\Models\LedgerModel;
use App\Http\Requests\Api\LedgerItem\IndexRequest;

class LedgerItemController extends ApiBaseController
{
    protected $model = LedgerItem::class;
   
    protected $indexRequest = IndexRequest::class;
}
