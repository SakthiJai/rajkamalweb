<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiBaseController;
use App\Models\ShippingDetail;
use Examyou\RestAPI\ApiResponse;
use App\Http\Requests\Api\ShippingDetail\IndexRequest;
use App\Http\Requests\Api\ShippingDetail\StoreRequest;
use App\Http\Requests\Api\ShippingDetail\UpdateRequest;
use App\Http\Requests\Api\ShippingDetail\DeleteRequest;
use App\Http\Requests\Api\ShippingDetail\ShippingRequest;
class ShippingController extends ApiBaseController
{
    protected $model = ShippingDetail::class;
    protected $indexRequest = IndexRequest::class;
    protected $storeRequest = StoreRequest::class;
    protected $updateRequest = UpdateRequest::class;
    protected $deleteRequest = DeleteRequest::class;


    public function shipping(ShippingRequest $request)
    {


        $object =new ShippingDetail();
        if(isset($request->editId) && $request->editId>0)
        {
            $object = ShippingDetail::where("id",$request->editId)->first();
        }
        $object->ledger_id = $request->ledger_id;
		$object->shipping_address = $request->shipping_address;
        $object->shipping_city = $request->shipping_city;
        $object->shipping_pincode = $request->shipping_pincode;
        $object->is_temporary = $request->is_temporary;
        $object->shipping_contact_number = $request->shipping_contact_number;
		$object->save();
        return ApiResponse::make("Resource created successfully", ["xid" => $object->id]);
    }


    public function addresseditshow($id)
    {
        try {
            $customer = ShippingDetail::where("id", $id)->first();
            if (!$customer) {
                return response()->json([
                    'success' => false,
                    'message' => 'Customer not found',
                ], 404);
            }
            return response()->json([
                'success' => true,
                'data' => $customer,
            ]);
        } catch (\Exception $ex) {
            return response()->json([
                'success' => false,
                'message' => $ex->getMessage(),
            ], 500);
        }
    }





}
