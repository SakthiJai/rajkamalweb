<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiBaseController;
use App\Http\Requests\Api\ReceiptPayment\IndexRequest;
use App\Http\Requests\Api\ReceiptPayment\StoreRequest;
use App\Http\Requests\Api\ReceiptPayment\UpdateRequest;
use App\Http\Requests\Api\ReceiptPayment\DeleteRequest;
use App\Http\Requests\Api\Sales\SalesCreateRequest;
use App\Models\PurchaseBillDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\ReceiptPayment;
use App\Models\Order;
use App\Models\LedgerModel;
use App\Models\PaymentModeModel;
use Carbon\Carbon;

class ReceiptPaymentController extends ApiBaseController
{
    protected $model = ReceiptPayment::class;
    protected $indexRequest = IndexRequest::class;
    protected $storeRequest = StoreRequest::class;
    protected $updateRequest = UpdateRequest::class;
    protected $deleteRequest = DeleteRequest::class;




    public function receiptPaymentDetails(SalesCreateRequest $request)
    {
        DB::beginTransaction();
        try {
            // Create new Order instance
            if($request->selectedInvoice=="null" || $request->selectedInvoice==null) {
                if (!$this->createReceiptPayment($request)) {
                    return response()->json(['message' => 'Payment Bill stored successfully.'], 200);
                }
            } else{
				if(!$this->updateReceiptPayment($request))
				{
					return response()->json(['message' => 'Order and items updated successfully.'], 201);
				}
			}
            DB::commit();
        } catch (\Illuminate\Database\QueryException $ex) {
            DB::rollback();
            return response()->json(['message' => $ex->getMessage()], 500);
        }
    }
    public function createReceiptPayment(SalesCreateRequest $request)
    {
        $orderItems = $request->input('items');
        if (!empty($orderItems)) {
            $item = $orderItems[0];
            
            $cr = DB::select("select recent_bill_number from settings where setting_type='payment_voucher'");
            
            $subtotal = array_sum(array_column($orderItems, 'amount'));
            $short_subtotal = array_sum(array_column($orderItems, 'short_amount'));

           $create= ReceiptPayment::create([
                "payment_voucher" => str_pad(((int)$cr[0]->recent_bill_number) + 1, 8, "0", STR_PAD_LEFT),
                "receiptpayment_mode" => $request->receiptpayment_mode,
                "narration" => $request->narration,
                "order_date" => $request->order_date,
                "party_id" => $item['item_id'],
                "station" => $item['quantity'],
                "receipt_date" => $item['receipt_date'],
                "short_amount" => (int)($item['short_amount']),
                "inst_no" => $item['inst_no'],
                "inst_type" => $item['inst_type'],
                "amount" => $item['amount'],
                "subtotal" => $subtotal,
                "short_subtotal" => $short_subtotal
            ]);
            if($create)
            {
                $this->savepayment($create,$request);
            }
    
            // Update recent bill number
            $sql = "update settings set recent_bill_number = '".($cr[0]->recent_bill_number + 1)."' where setting_type = 'payment_voucher'";
            DB::update($sql);
        }
    
        return response()->json(['message' => 'Receipt payment saved successfully.'], 200);
    }
    

    //my try
    public function savepayment($paymentRef,Request $request)
    {
        $totalAmount = 0;
       
        foreach ($request->invoiceitems as $key => $value) { 
           
            if (isset($value['paid_amount']) && $value['paid_amount'] > 0) {  
                $orderId = PurchaseBillDetail::select("id","total_amount","due_amount")->where("invoice_number",$value['invoice_number'])->first();
                $newPay = PaymentModeModel::insert([
                        'amount' => $value['paid_amount'],
                        'settlement_mode' =>$request->receiptpayment_mode,
                        'bill_number' => $value['invoice_number'],
                        'order_id' => $orderId->id,                       
                        'payment_reference' =>  $paymentRef->id,
                        'payment_type' => 'purchase',
                        'payment_added_by' => '1',
                        'payment_added_at' => Carbon::now(),
                        'login_user_id' => auth('api')->user() ? auth('api')->user()->id : null,
                        //'balance_adjusted' => $balanceAdjusted // Store the calculated balance adjusted
                    ]);
                    
                     if( $value['paid_amount'] >= $orderId->due_amount)
                     {
                         PurchaseBillDetail::where('id',$orderId->id)->update(['payment_status' => 'Paid','due_amount'=>0]);
                     }
                    else{

                         PurchaseBillDetail::where('id',$orderId->id)->update(['payment_status' => 'Partially paid','due_amount'=>($orderId->due_amount-$value['paid_amount'])]);
                     }


                
            }
        }
        
        return response()->json(['message' => 'Receipt Saved successfully.'], 201);
    }



    public function billNumber(Request $request)
    {
        try {
            $bill = DB::select("SELECT recent_bill_number FROM settings WHERE setting_type='bill_number'");

            if (isset($request->party_id)) {
                $customerDetails = LedgerModel::select('opening_balance')->where('id', $request->party_id)->first();
            }

            return response()->json([
                'message' => 'Data retrieved successfully',
                'data' => [
                    "ref"     => str_pad(($bill[0]->recent_bill_number + 1), 8, "0", STR_PAD_LEFT),
                    "balance" => $customerDetails->opening_balance ?? "",
                ]
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error fetching bill number: ' . $e->getMessage());
            return response()->json(['message' => 'Error fetching bill number.'], 500);
        }
    }


    public function deletePayment($id)
    {
       // Log::error("No expense found with ID: {$id}");

        $exp = ReceiptPayment::find($id);
         $exp->delete();

        return response()->json(['message' => 'Data updated successfully', 201]);
    }



    
    public function getInvoicePaymentDetails(Request $request)
    {
        try {
            $invoiceData = ReceiptPayment::where("payment_voucher", $request->invoice)->get(); // Fetch multiple records
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

   
    public function updateReceiptPayment(SalesCreateRequest $request)
    {
        $orderItems = $request->input('items');
        if (!empty($orderItems)) {
            $item = $orderItems[0];
    
            // Get recent bill number for the payment voucher
            $cr = DB::select("select recent_bill_number from settings where setting_type='payment_voucher'");
    
            $subtotal = array_sum(array_column($orderItems, 'amount'));
            $short_subtotal = array_sum(array_column($orderItems, 'short_amount'));
    
            $paymentVoucher = $request->input('payment_voucher');
    
            // Prepare the update data
            $updateData = [
                "receiptpayment_mode" => $request->receiptpayment_mode,
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
    
            $updated = DB::table('receipt_payments')
                ->where('payment_voucher', $paymentVoucher) // Make sure this is a unique identifier
                ->update($updateData);
    
            if ($updated) {
            
                DB::update("update settings set recent_bill_number = '".($cr[0]->recent_bill_number + 1)."' where setting_type = 'payment_voucher'");
    
                return response()->json(['message' => 'Receipt payment updated successfully.'], 200);
            } else {
                return response()->json(['message' => 'No record found to update.'], 404);
            }
        }
    
        return response()->json(['message' => 'No order items found.'], 400);
    }
    
    
}
