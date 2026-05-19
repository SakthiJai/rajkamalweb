<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiBaseController;
use App\Http\Requests\Api\Receipt\IndexRequest;
use App\Http\Requests\Api\Receipt\StoreRequest;
use App\Http\Requests\Api\Receipt\UpdateRequest;
use App\Http\Requests\Api\Sales\SalesCreateRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Receipt;
use App\Models\Order;
 use App\Models\LedgerModel;
 use App\Models\PurchaseBillItemsDetail;
 use App\Models\PaymentModeModel;
 use Carbon\Carbon;


class ReceiptController extends ApiBaseController

{
    protected $model = Receipt::class;
    protected $indexRequest = IndexRequest::class;
    protected $storeRequest = StoreRequest::class;
    protected $updateRequest = UpdateRequest::class;


    public function receiptDetails( SalesCreateRequest $request)
	{
		DB::beginTransaction();
		try {

			// Create new Order instance
			if($request->selectedInvoice=="null")
			{
				if(!$this->createReceipt($request))
				{
					return response()->json(['message' => 'Recipt Bill stored successfully.'], 201);
				}
			}
            else{
				if(!$this->updateReceipt($request))
				{
					return response()->json(['message' => 'Order and items updated successfully.'], 201);
				}
			}
			DB::commit();
		}
		catch(\Illuminate\Database\QueryException $ex){
			//dd($ex->getMessage());
			DB::rollback();
			return response()->json(['message' => $ex->getMessage()], 500);
			// Note any method of class PDOException can be called on $ex.
		  }

	}

public function createReceipt(SalesCreateRequest $request)
{

        $orderItems = $request->input('items');
        if (!empty($orderItems)) {

            $item = $orderItems[0];
            $cr = DB::select("select recent_bill_number from settings where setting_type='voucher_number'");
            $subtotal = array_sum(array_column($orderItems, 'amount'));
            $short_subtotal = array_sum(array_column($orderItems, 'short_amount'));
            $create= Receipt::create([
                "voucher_number"=>str_pad(((int)$cr[0]->recent_bill_number)+1,8,"0",STR_PAD_LEFT),
                "receipt_mode" =>$request->receipt_mode,
                "narration" => $request->narration,
                "order_date" => $request->order_date,
                "party_id" => $item['item_id'],
                "station" =>$item ['station'],
                "receipt_date" => $item['receipt_date'] ,
                "inst_no" => $item['inst_no'],
                "inst_type" => $item['inst_type'],
                "short_amount" => (int)($item['short_amount']),
                "amount" =>$item['amount'],
                "subtotal" => $subtotal,
                "short_subtotal" => $short_subtotal
                ]);

                if($create)
            {
                $this->savepayment($create,$request);
            }

            $sql = "update settings set recent_bill_number = '".($cr[0]->recent_bill_number+1)."'  where setting_type = 'voucher_number'";
            DB::update($sql);

        }
        return response()->json(['message' => 'Receipt saved successfully.'], 200);

    }

 //my try
 public function savepayment($paymentRef,Request $request)
 {
     $totalAmount = 0;
    // $totalBillAmount = $request->data[0]['bill_amount'];  // Assuming bill_amount is the same for all items
    // $totalBillAmount = (float) round($request->data[0]['bill_amount'], 2); // Ensure consistent rounding
 
 
     foreach ($request->invoiceitems as $key => $value) { 
        
         if (isset($value['paid_amount']) && $value['paid_amount'] > 0) {  
             $orderId = Order::select("id","total")->where("invoice_number",$value['invoice_number'])->first();
             //$beforetotalAmount = PaymentModeModel::where('order_id',$orderId->id)->sum('amount');
             //$balanceAdjusted = $value['bill_amount'] - $value['amount'];  // Assuming this is the logic for balance adjustment
             $newPay = PaymentModeModel::insert([
                     'amount' => $value['paid_amount'],
                     'settlement_mode' =>$request->receipt_mode,
                     'bill_number' => $value['invoice_number'],
                    'order_id' => $orderId->id,
                     //'cash_return' => $value['cash_return'],
                     'payment_reference' =>  $paymentRef->id,
                     'payment_type' => 'sales',
                     'payment_added_by' => '1',
                     'payment_added_at' => Carbon::now(),
                     'login_user_id' => auth('api')->user() ? auth('api')->user()->id : null,
                     //'balance_adjusted' => $balanceAdjusted // Store the calculated balance adjusted
                 ]);
                 $totalAmount = PaymentModeModel::where('order_id',$orderId->id)->sum('amount');
                 if( $totalAmount >= $orderId->total)
                 {
                     Order::where('id',$orderId->id)->update(['payment_status' => 'Paid','due_amount'=>0]);
                 }
                 else{

                     Order::where('id',$orderId->id)->update(['payment_status' => 'Partially paid','due_amount'=>($orderId->total-$value['paid_amount'])]);
                 }


             //$totalAmount += $value['amount'];
         }
     }
     /*$totalAmount = round($totalAmount, 2);
     $invoice_number = isset($request->data[0]['invoice_number'])?$request->data[0]['invoice_number']:$request->data[0]['bill_number'];
 
     //\Log::info("Total Paid: $totalAmount, Total Bill: $totalBillAmount, Invoice: $invoice_number");
     if ($totalAmount >= $totalBillAmount)
     {
         Order::where('invoice_number', $invoice_number)
             ->update(['payment_status' => 'Paid']);
     }
     elseif ($totalAmount > 0)
      {
         Order::where('invoice_number', $invoice_number)
             ->update(['payment_status' => 'Partially paid']);
     }*/
     // Return a success response
     return response()->json(['message' => 'Sales Entry Saved successfully.'], 201);
 }


    


    public function billNumber(Request $request)
	{

		$bill = DB::select("select recent_bill_number from settings where setting_type='bill_number'");

		if(isset($request->party_id))
		$customerDetails 	= LedgerModel::select('opening_balance')->where('id',$request->party_id)->first();
		//print_r($bill);
		//echo str_pad(($bill[0]->recent_bill_number+1),8,"0",STR_PAD_LEFT); ;
		return response()->json([
			'message' => 'Data retrived successfully',
			'data'=>["ref"=> str_pad(($bill[0]->recent_bill_number+1),8,"0",STR_PAD_LEFT),
			//"discountItems"=>$discountItems,

			"balance"=>$request->party_id?$customerDetails->opening_balance:""]
		], 200);
	}

    public function deleteReceipt($id)
    {
       // Log::error("No expense found with ID: {$id}");

        $exp = Receipt::find($id);
         $exp->delete();

        return response()->json(['message' => 'Data updated successfully', 201]);
    }

    public function getInvoiceReceiptDetails(Request $request)
    {
        try {
            $invoiceData = Receipt::where("voucher_number", $request->invoice)->get(); // Fetch multiple records
            $ledgerIds = $invoiceData->pluck('party_id')->unique();

            $partyDetails = LedgerModel::whereIn('id', $ledgerIds)->get(); 
    
            return response()->json([
                'message' => 'Data retrieved successfully',
                'data' => [
                    "invoiceData" => $invoiceData, 
                    "partyDetails" => $partyDetails
                ]
            ], 200);
        } catch (\Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 500);
        } catch (\Illuminate\Database\QueryException $ex) {
            return response()->json(['message' => $ex->getMessage()], 500);
        }
    }

    public function updateReceipt(SalesCreateRequest $request)
    {
        $orderItems = $request->input('items');
        if (!empty($orderItems)) {
            $item = $orderItems[0];
    
            // Get recent bill number for the payment voucher
            $cr = DB::select("select recent_bill_number from settings where setting_type='voucher_number'");
    
            $subtotal = array_sum(array_column($orderItems, 'amount'));
            $short_subtotal = array_sum(array_column($orderItems, 'short_amount'));
    
            $paymentVoucher = $request->input('voucher_number');
    
            // Prepare the update data
            $updateData = [
                "receipt_mode" =>$request->receipt_mode,
                "narration" => $request->narration,
                "order_date" => $request->order_date,
                "party_id" => $item['item_id'],
                "receipt_date" => $item['receipt_date'],
                "short_amount" => (int)($item['short_amount']),
                "inst_no" => $item['inst_no'],
                "inst_type" => $item['inst_type'],
                "amount" => $item['amount'],
                "subtotal" => $subtotal,
                "short_subtotal" => $short_subtotal
            ];
    
            $updated = DB::table('receipt_accounttrans')
                ->where('voucher_number', $paymentVoucher) // Make sure this is a unique identifier
                ->update($updateData);
    
            if ($updated) {
            
                DB::update("update settings set recent_bill_number = '".($cr[0]->recent_bill_number + 1)."' where setting_type = 'voucher_number'");
    
                return response()->json(['message' => 'Receipt payment updated successfully.'], 200);
            } else {
                return response()->json(['message' => 'No record found to update.'], 404);
            }
        }
    
        return response()->json(['message' => 'No order items found.'], 400);
    }


}
