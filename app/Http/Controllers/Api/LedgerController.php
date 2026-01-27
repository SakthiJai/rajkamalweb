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

<<<<<<< Updated upstream
=======


>>>>>>> Stashed changes
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

<<<<<<< Updated upstream
=======
    public function storee(Request $request)
{
    try {
        // Validate required fields
        // $validated = $request->validate([
        //     'party_name' => 'required|string',
        //     // Add more validations as needed
        // ]);

        // Create ledger
        $ledger = new LedgerModel();
        $ledger->party_name = $request->input('party_name');
        $ledger->trade_name = $request->input('trade_name');
        //$ledger->party_type = $request->input('party_type');
        $ledger->customer_email = $request->input('customer_email');
        $ledger->address = $request->input('address');
        //$ledger->city = $request->input('city');
       // $ledger->pincode = $request->input('pincode');
        //$ledger->state = $request->input('state');
       // $ledger->country = $request->input('country');
       // $ledger->account_group = $request->input('account_group');
        //$ledger->category = $request->input('category');
        //$ledger->mobile = $request->input('mobile');
        //$ledger->whatsapp = $request->input('whatsapp');
        $ledger->save();

        // Save product items if present
        if ($request->has('inputFields')) {
            foreach ($request->input('inputFields') as $product) {
                LedgerItem::create([
                    'ledger_id' => $ledger->id,
                    'keyword'   => $product['keyword'] ?? '',
                    'value'     => $product['value'] ?? '',
                ]);
            }
        }

        // Save contact items if present
        if ($request->has('contactFields')) {
            foreach ($request->input('contactFields') as $contact) {
                ContactModel::create([
                    'ledger_id' => $ledger->id,
                    'contact_tittle' => $contact['contact_tittle'] ?? '',
                    'contact_number' => $contact['contact_number'] ?? '',
                    'contact_whatsapp_number' => $contact['contact_whatsapp_number'] ?? '',
                    'contact_email_id' => $contact['contact_email_id'] ?? '',
                    'contact_name' => $contact['contact_name'] ?? '',
                    'contact_last_name' => $contact['contact_last_name'] ?? '',
                    'contact_designation' => $contact['contact_designation'] ?? '',
                ]);
            }
        }

        return response()->json([
            'message' => 'Ledger created successfully',
            'ledger' => $ledger,
        ], 201);

    } catch (\Exception $e) {
    // Return error as JSON
    return response()->json([
        'error' => $e->getMessage(),
    ], 500);
}
}

    public function saleseditshow($id)
    {
        try {
            $customer = LedgerCustomerModel::where("id", $id)->first();
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



>>>>>>> Stashed changes
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
