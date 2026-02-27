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
use App\Models\BankDetail;
use App\Models\ShippingDetail;
use App\Models\ContactModel;
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


        $object =new LedgerCustomerModel();
        if(isset($request->editId) && $request->editId>0)
        {
            $object = LedgerCustomerModel::where("id",$request->editId)->first();
        }

		$object->cus_name = $request->cus_name;
        $object->mobile_number = $request->mobile_number;
        $object->gender = $request->gender;
        $object->age = $request->age;
        $object->billing_discount = $request->billing_discount;
        $object->customer_type = $request->customer_type;
        $object->customer_city = $request->customer_city;
        $object->address = $request->address;
        $object->email = $request->email;
        $object->whatsapp_numbner = $request->whatsapp_numbner;
        $object->government_id = $request->government_id;
        $object->pin_number = $request->pin_number;
        $object->phone_number = $request->phone_number;
        $object->dob = $request->dob;
        $object->ledger_id = $request->ledger_id;
        $object->customer_state = $request->customer_state;

		$object->save();
        return ApiResponse::make("Customer Contact created successfully", ["xid" => $object->id]);
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



    public function storeGeoLocaltion(Request $request)
    {

        $ledger = new LedgerModel();


        $ledger->save();


        $productItems = $request->input('inputFields');


        foreach ($productItems as $product) {
            LedgerItem::create([
                'ledger_id' => $ledger->id, // Use the saved ledger's ID
                'keyword'   => $product['keyword'],
                'value'     => $product['value'],
            ]);
        }
        $contactItems = $request->input('contactFields');
        foreach ($contactItems as $contact) {
            ContactModel::create([
                'ledger_id' => $ledger->id, // Use the saved ledger's ID
                'contact_tittle'   => $contact['contact_tittle'],
                'contact_number'     => $contact['contact_number'],
                'contact_whatsapp_number'     => $contact['contact_whatsapp_number'],
                'contact_email_id'     => $contact['contact_email_id'],
                'contact_name'     => $contact['contact_name'],
                'contact_last_name'     => $contact['contact_last_name'],
                'contact_designation'     => $contact['contact_designation'],
            ]);
        }
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



    public function saveContactInformation(Request $request)
    {
        foreach ($request->data as $information) {
            $data = new ContactModel();
            if (
                !empty($information['contact_name']) &&
                !empty($information['contact_number']) &&
                !empty($information['contact_name'])
            ) {
                $data->ledger_id = $information['id']; // Use the ledger's ID
                $data->contact_tittle = $information['contact_tittle'];
                $data->contact_number = $information['contact_number'];
                $data->contact_whatsapp_number = $information['contact_whatsapp_number'];
                $data->contact_email_id = $information['contact_email_id']; // "Savings" or "Current"
                $data->contact_name = $information['contact_name'];
                $data->contact_last_name = $information['contact_last_name'];
                $data->contact_designation = $information['contact_designation'];
                $data->save();
            }
        }

    }


    public function saveBankDetails(Request $request)
    {
        foreach ($request->data as $information) {
            $data = new BankDetail();
            if (
                !empty($information['bank_bank_name']) &&
                !empty($information['bank_account_number']) &&
                !empty($information['bank_account_type'])
            ) {
                $data->ledger_id = $information['id']; // Use the ledger's ID
                $data->bank_bank_name = $information['bank_bank_name'];
                $data->bank_account_number = $information['bank_account_number'];
                $data->bank_branch = $information['bank_branch'];
                $data->bank_account_type = $information['bank_account_type']; // "Savings" or "Current"
                $data->bank_ifsc_code = $information['bank_ifsc_code'];
                $data->bank_account_holder_name = $information['bank_account_holder_name'];
                $data->save();
            }
        }

        return response()->json(['message' => 'Bank details created successfully' , 'BankDetail' => $data], 201);
    }


    public function saveShippingDetails(Request $request)
    {
        foreach ($request->data as $information) {
            $data = new ShippingDetail();
            if (
                !empty($information['shipping_address']) &&
                !empty($information['shipping_city']) &&
                !empty($information['shipping_pincode'])
            ) {
                $data->ledger_id = $information['id'];
                $data->shipping_address = $information['shipping_address'];
                $data->shipping_city = $information['shipping_city'];
                $data->shipping_pincode = $information['shipping_pincode'];
                $data->is_temporary = $information['is_temporary'] ?? 0 ;
                $data->shipping_contact_number = $information['shipping_contact_number'] ;

                $data->save();
            }
        }

        return response()->json(['message' => 'Shipping details created successfully', 'ShippingDetail' => $data], 201);
    }


    public function showledgerlist(Request $request, $id)
    {
        // Retrieve the sales number using the provided $id
        $data       =   LedgerModel::find($id);
        $contact    =   ContactModel::where("ledger_id",$id)->get();
        $bank       =   BankDetail::where("ledger_id",$id)->get();
        $ship       =   ShippingDetail::where("ledger_id",$id)->get();
        $custom     =   LedgerItem::where("ledger_id",$id)->get();

        if (!$data) {
            return response()->json(['error' => 'Sale not found'], 404);
        }

        return response()->json(['message' => 'Data retrived successfully' ,
        "ship"=>$ship,
         'ledger' => $data,
         'contact'=>$contact,
         "bank"=>$bank,

         "custom"=>$custom
         ]
         , 200);
    }

    public function showledgerproductlist(Request $request, $id)
    {
        // Retrieve the sales number using the provided $id
        $sale = LedgerItem::find($id);

        if (!$sale) {
            return response()->json(['error' => 'Sale not found'], 404);
        }

        return response()->json($sale);
    }


    // check dublicatemobile//

    public function duplicateCheckMobile(Request $request)
    {
        $duplicate = LedgerCustomerModel::where('mobile_number',trim($request->customers))->first();

        if($duplicate)
        {
            echo 500;
        }
        else
        {
            echo 200;
        }
    }
    // end dublicate mobile//


    public function duplicateCheckname(Request $request, $party_name, $id)
    {
        $duplicate = LedgerModel::where('party_full_name', trim($party_name));

        // Check if the id is greater than 0 to exclude the current record
        if ($id > 0) {
            $duplicate->where("id", "!=", $id);
        }

        // Check if there are any duplicates
        if ($duplicate->count() > 0) {
            return response()->json(['status' => 500, 'message' => 'Party name already exists!'], 500);
        } else {
            return response()->json(['status' => 200, 'message' => 'Party name is available'], 200);
        }
    }
     public function updateLedger(Request $request)
     {
        $id = (int)$request->id;
        try{
        $data = LedgerModel::where('id',trim($request->id))->first();
      ///  $input = $request->all();
        $updateData = [
                        "party_name"=>$request->party_name,
                        "party_full_name"=>$request->party_name,
                        "station"=>$request->station,
                        "Address"=>$request->address,
                        "stock_country"=>$request->stock_country,
                        "stock_state"=>$request->stock_state,
                        "stock_city"=>$request->stock_city,
                        "parent_ledger"=>$request->parent_ledger,
                        "account_group"=>$request->account_group,
                        "mail_to"=>$request->mail_to,
                        "stock_pincode"=>$request->stock_pincode,
                        "balancing_method"=>$request->balancing_method,
                        "opening_balance"=>$request->opening_balance,
                        "credit_type"=>$request->credit_type,
                        "credit_days"=>$request->credit_days,
                        "phone_number"=>$request->credit_days,
                        "mobile_number"=>$request->mobile_number,
                        "whatsapp_number"=>$request->whatsapp_number,
                        "ledger_type"=>$request->ledger_type,
                        "pan_number"=>$request->pan_number,
                        "bank_name"=>$request->bank_name,
                        "ifsc_code"=>$request->ifsc_code,
                        "account_number"=>$request->account_number,
                        "branch"=>$request->branch,
                        "account_type"=>$request->account_type,
                        "account_holder_name"=>$request->account_holder_name,
                        "gst_number"=>$request->gst_number,
                        "party_type"=>$request->party_type,
                        "category"=>$request->category,
                        "ship_city"=>$request->ship_city,
                        "ship_address"=>$request->ship_address,
                        "ship_pincode"=>$request->ship_pincode,
                        "ship_contactno"=>$request->ship_contactno,
                        "trade_name"=>$request->trade_name,


                    ];
        $data->fill($updateData)->save();
        $contactItems = $request->input('contactFields');
        $contact    =   ContactModel::where("ledger_id",trim($request->id))->delete();
        $bank       =   BankDetail::where("ledger_id",trim($request->id))->delete();
        $ship       =   ShippingDetail::where("ledger_id",trim($request->id))->delete();
         $input     =   LedgerItem::where("ledger_id",trim($request->id))->delete();
         if ($request->ship && sizeof($request->ship) > 0)
         {
            $shipitems = $request->input('ship');

            foreach ($shipitems as $ship)
            {
                $ShippingDetail = new ShippingDetail();
                $ShippingDetail->ledger_id = $id;
                $ShippingDetail->shipping_address = $ship['shipping_address'] ;
                $ShippingDetail->shipping_city = $ship['shipping_city'] ;
                $ShippingDetail->shipping_pincode = $ship['shipping_pincode'];
                $ShippingDetail->is_temporary = $ship['is_temporary'];
                $ShippingDetail->shipping_contact_number = $ship['shipping_contact_number'];
                $ShippingDetail->save();
            }
        }
        if ($request->shippingDetails && sizeof($request->shippingDetails) > 0)
        {
           $shipitems = $request->input('shippingDetails');
           foreach ($shipitems as $ship) {
            $ShippingDetail = new ShippingDetail();
            $ShippingDetail->ledger_id = $id;
            $ShippingDetail->shipping_address = $ship['shipping_address'] ?? '';
            $ShippingDetail->shipping_city = $ship['shipping_city'] ?? '';
            $ShippingDetail->shipping_pincode = $ship['shipping_pincode'] ?? '';
            $ShippingDetail->is_temporary = $ship['is_temporary'] ?? '';
            $ShippingDetail->shipping_contact_number = $ship['shipping_contact_number'] ?? '';
            $ShippingDetail->save();
        }
       }
         if($request->inputFields && sizeof($request->inputFields)>0)
        {
             foreach ($request->inputFields as $input) {
                LedgerItem::create([
                'ledger_id' => $id, // Use the saved ledger's ID
                'keyword'   => $input['keyword'],
                'value'     => $input['value'],
            ]);
            }
        }
        if($contactItems && sizeof($contactItems)>0)
        {

            foreach ($contactItems as $contact) {

                ContactModel::create([
                    'ledger_id'         => trim($request->id), // Use the saved ledger's ID
                    'contact_tittle'    => $contact['contact_tittle'],
                    'contact_number'    => $contact['contact_number'],
                    'contact_whatsapp_number'     => $contact['contact_whatsapp_number'],
                    'contact_email_id'          => $contact['contact_email_id'],
                    'contact_name'     => $contact['contact_name'],
                    'contact_last_name'     => $contact['contact_last_name'],
                    'contact_designation'     => $contact['contact_designation'],
                ]);
            }
        }

        if($request->bankDetails && sizeof($request->bankDetails)>0)
        {
            $bankitems = $request->input('bankDetails');
            foreach ($bankitems as $bank) {
                $BankDetail = new BankDetail();
                $BankDetail->ledger_id = $id;
                $BankDetail->bank_bank_name = $bank['bank_bank_name'];
                $BankDetail->bank_account_number = $bank['bank_account_number'];
                $BankDetail->bank_branch =  $bank['bank_branch'] ;
                $BankDetail->bank_account_type =   $bank['bank_account_type'];
                $BankDetail->bank_ifsc_code =  $bank['bank_ifsc_code'] ;
                $BankDetail->bank_account_holder_name =   $bank['bank_account_holder_name'];
                $BankDetail->save();
            }
        }


        return response()->json(['message' => 'Party updated successfully'], 200);
        }
    catch(\Illuminate\Database\QueryException $ex){
			//dd($ex->getMessage());
			//DB::rollback();
			return response()->json(['message' => $ex->getMessage()], 500);
 		  }
     }

     // end dublicate name//

//customer dublicate///
public function duplicateCheckcustomername(Request $request, $cus_name, $id)
{
    $mobile_number = $request->input('mobile_number');
    $whatsapp_numbner = $request->input('whatsapp_numbner');
    $duplicate = LedgerCustomerModel::where('cus_name', trim($cus_name));
    if ($mobile_number) {
        $duplicate->where('mobile_number', $mobile_number);
    }
    if ($whatsapp_numbner) {
        $duplicate->orWhere('whatsapp_numbner', $whatsapp_numbner);
    }
    if ($id > 0) {
        $duplicate->where("id", "!=", $id);
    }
    if ($duplicate->count() > 0) {
        return response()->json(['status' => 500, 'message' => 'Contact already exists'], 500);
    } else {
        return response()->json(['status' => 200, 'message' => 'Contact is available'], 200);
    }
}

//end of customer dublicate//









}
