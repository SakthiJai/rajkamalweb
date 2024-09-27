<?php

namespace App\Http\Controllers\Api;

use App\Models\LedgerModel;
use App\Models\LedgerCustomerModel;
use Examyou\RestAPI\ApiResponse;
use App\Http\Controllers\ApiBaseController;
use App\Http\Requests\Api\Ledger\IndexRequest;
use App\Http\Requests\Api\Ledger\CustomerRequest;
use App\Http\Requests\Api\Ledger\DeleteRequest;
use App\Http\Requests\Api\Ledger\StoreRequest;
use App\Http\Requests\Api\Ledger\UpdateRequest;
use Illuminate\Http\Request;
class LedgerController extends ApiBaseController
{
    protected $model = LedgerModel::class;
    protected $indexRequest = IndexRequest::class;
    protected $storeRequest = StoreRequest::class;
    protected $updateRequest = UpdateRequest::class;
    protected $deleteRequest = DeleteRequest::class;

    public function customer(CustomerRequest $request)
    {
        
        $customer = new LedgerCustomerModel();
       
		$object =new LedgerCustomerModel();
		$object->name = $request->name;
        $object->mobile_number = $request->mobile_number;
        $object->ledger_id = $request->ledger_id;
       
		$object->save();
        return ApiResponse::make("Resource created successfully", ["xid" => $object->xid]);
    }
}
