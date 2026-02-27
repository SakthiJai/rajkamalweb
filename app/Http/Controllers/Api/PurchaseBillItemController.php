<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiBaseController;
use Illuminate\Http\Request;
use App\Http\Requests\Api\PurchaseBillItem\ItemIndexRequest;
use App\Http\Requests\Api\PurchaseBillItem\ItemStoreRequest;
use App\Http\Requests\Api\PurchaseBillItem\ItemUpdateRequest;
use App\Http\Requests\Api\PurchaseBillItem\ItemDeleteRequest;
use App\Models\PurchaseBillItemDetail;

class PurchaseBillItemController extends ApiBaseController
{
    // Define the model used by this controller
    protected $model = PurchaseBillItemDetail::class;

    // Define the request classes for handling validation
    protected $indexRequest = ItemIndexRequest::class;
    protected $storeRequest = ItemStoreRequest::class;
    protected $updateRequest = ItemUpdateRequest::class;
    protected $deleteRequest = ItemDeleteRequest::class;

    // Store a new item detail

}
