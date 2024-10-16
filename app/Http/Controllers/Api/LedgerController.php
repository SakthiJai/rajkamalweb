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
use App\Models\LedgerItem;
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
        return ApiResponse::make("Resource created successfully", ["xid" => $object->id]);
    }

    public function storeGeoLocaltion(Request $request)
    {
       
        $ledger = new LedgerModel();
        $ledger->latitude = $request->latitude;
      
        $ledger->save();
        
        // Get product items from the request
        $productItems = $request->input('inputFields');
    
       
        foreach ($productItems as $product) { 
            LedgerItem::create([
                'ledger_id' => $ledger->id, // Use the saved ledger's ID
                'keyword'   => $product['keyword'],
                'value'     => $product['value'],
            ]);
        }
    
        // Return a success response with the created ledger
        return response()->json(['message' => 'Ledger created successfully', 'geolocation' => $ledger], 201);
    }
    public function saveCustomInformation(Request $request)
    {
        
        foreach ($request->data as $information) { 
            $data  = new LedgerItem();
           
                $data->ledger_id =$information['id']; // Use the saved ledger's ID
                $data->keyword =$information['keyword'];
                $data->value =$information['value'];
                $data->save();
                return response()->json(['message' => 'Custom Detalis created successfully', 'LedgerItem' => $data], 201);
            
        }
    }

}
