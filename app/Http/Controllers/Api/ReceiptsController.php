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
use App\Models\ReceiptItems;
use App\Models\PurchaseBillItemsDetail; 

class ReceiptsController extends ApiBaseController

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
					return response()->json(['message' => 'Purchase Bill stored successfully.'], 201);
				}
			}
			else{
				if(!$this->updatebill($request))
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



    public function createReceipt($request)
	{
		DB::beginTransaction();
		try{
		$issue = false;
		$invoiceData = Order::where("invoice_number", $request->invoice_number)->first();
			$order = new Receipt();
			
			//$order->invoice_number    	= $request->bill_number;
			//$order->order_id     		=	$invoiceData->id;
			$order->receipt_mode        	= $request->receipt_mode;
            $order->narration        	= $request->narration;
			
			if($order->save())
			{


			}
			else{ 
				echo $issue= true; 
			}	
			$sql = "update settings set recent_bill_number = '".($request->bill_number+1)."'  where setting_type = 'cr_number'";
				DB::update($sql);

			// Get the items from the request
			$orderItems = $request->input('items');

			if ($order && !empty($orderItems)) {
				//$total = 0;
				foreach ($orderItems as $item) { 
					// Check if item_id and item_name are present and valid
					if (!is_null($item['item_id']) && !is_null($item['single_unit_price']) && !is_null($item['quantity'])) {
						$quantity = !empty($item['quantity']) ? $item['quantity'] : 1;
						$amount = $item['single_unit_price'] * $quantity;
						// Insert order items
						if(ReceiptItems::create([
							//'user_id'            => auth('api')->user()->id,
							'order_id'           => $order->id,
							'party_id'           => (int)$item['item_id'],
							'station'           => $quantity,
							'receipt_date'         => isset($item['free']) ? $item['free'] : 0,
							'amount'         => $item['single_unit_price'],
                            'short_amount'         => $item['short_amount'],
							
						]))
						{
							//$total = $total+$amount;
						}
						else{
							echo 'issue2'; 
						}
					}
				}
				

			}
			DB::commit();
			return $issue;
		}
		catch(\Illuminate\Database\QueryException $ex){  echo '333';
			//dd($ex->getMessage()); 
			DB::rollback();
			echo  response()->json(['message' => $ex->getMessage()], 500);
			// Note any method of class PDOException can be called on $ex.
		  }
		catch (\Exception $e) {
			DB::rollBack();

            echo  response()->json(['message' =>  $e->getMessage()], 500);
		
			Log::error('Error storing order: ' . $e->getMessage(), [
				'file' => $e->getFile(),
				'line' => $e->getLine(),
				'trace' => $e->getTraceAsString(),
			]);

			return response()->json(['message' => 'Error storing order, please try again.'], 500);
		}
			
			
	}
}
