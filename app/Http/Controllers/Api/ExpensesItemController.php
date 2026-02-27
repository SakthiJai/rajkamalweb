<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Api\PurchaseReturn\IndexRequest;
use App\Http\Requests\Api\PurchaseReturn\StoreRequest;
use App\Http\Requests\Api\PurchaseReturn\UpdateRequest;
use App\Http\Requests\Api\Sales\SalesCreateRequest;
use App\Models\ExpensesItems;
use App\Models\ExpensesItemDetalis; 
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\LedgerModel;
use App\Models\LedgerCustomerModel;

use App\Http\Controllers\ApiBaseController;

class ExpensesItemController extends ApiBaseController
{
   
    protected $model = ExpensesItems::class;
    protected $indexRequest = IndexRequest::class;
    protected $storeRequest = StoreRequest::class;
    protected $updateRequest = UpdateRequest::class;


    public function purchaseExpenseDetails( SalesCreateRequest $request)
	{
		DB::beginTransaction();
		try {

			// Create new Order instance
			if($request->selectedInvoice=="null")
			{
				if(!$this->createExpense($request))
				{
					return response()->json(['message' => 'Expense stored successfully.'], 201);
				}
			}
			else{
				if(!$this->updateexpense($request))
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



    public function createExpense($request)
	{
		DB::beginTransaction();
		try{
		$issue = false;
		$invoiceData = Order::where("invoice_number", $request->invoice_number)->first();
			$order = new ExpensesItems();

            
			
			$order->category_id    	= $request->expense_category;
            $order->expense_number    	= $request->expense_number;
            $order->original_invoice_number	    	= $request->original_invoice_number	;
            $order->payment_mode	    	= $request->payment_mode;
            $order->payment_mode_from	    	= $request->payment_mode_from;
			$order->order_date        	= $request->order_date;
            $order->address        	= $request->address;
            //$order->invoice_date        	= $request->invoice_date;
			$order->party_id          	= $request->party_id;
			$order->party_customer_id 	= $request->party_customer_id;
			$order->total_amount      	= ($request->total+$request->tax_amount);
			$order->tax_amount      	= ($request->tax_amount);
			$order->total_discount      = ($request->total_discount);
			$order->total_items      	= ($request->total_items);
			//$order->total_discount      	= ($request->total_discount);

		
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
				$total = 0;
				foreach ($orderItems as $item) { 
					// Check if item_id and item_name are present and valid
					if (!is_null($item['item_id']) && !is_null($item['single_unit_price']) && !is_null($item['quantity'])) {
						$quantity = !empty($item['quantity']) ? $item['quantity'] : 1;

						// Calculate the amount: single unit price * quantity
						$amount = $item['single_unit_price'] * $quantity;
						///$free = isset($item['free']) ? $item['free'] : 0;
							
						// Insert order items
						if(ExpensesItemDetalis::create([
							'user_id'            => auth('api')->user()->id,
							'order_id'           => $order->id,
							'product_id'        => (int)$item['item_id'],
							'quantity'           => $quantity,
							'unit_price'         => $item['single_unit_price'],
							'single_unit_price'  => $item['single_unit_price'],
							'free'              => $item['free'],
							//'tax_rate'           => 2,
							'discount_rate'      => $item['discount_rate'] ?? 0,
							'subtotal'           => $amount
						]))
						{
							$total = $total+$amount;
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

	public function getExpensenvoiceDetails(Request $request)
	{
		try{
		$invoiceData 	=	ExpensesItems::where("expense_number",$request->invoice)->first();
		//print_r($invoiceData);
		$customerData 	=	LedgerCustomerModel::where("id",$invoiceData->party_customer_id)->first();
		$invoiceItems	=	ExpensesItemDetalis::where('order_id',$invoiceData->id)->get();
		return response()->json([
			'message' => 'Data retrived successfullt',
			'data'=>["invoiceData"=>$invoiceData,"customerData"=>$customerData,"invoiceItems"=>$invoiceItems]
		], 200);
		}
		catch(\Exception $ex)
		{
			return response()->json(['message' => $ex->getMessage()], 500);
		}
		catch(\Illuminate\Database\QueryException $ex){
			//dd($ex->getMessage());
			return response()->json(['message' => $ex->getMessage()], 500);
			// Note any method of class PDOException can be called on $ex.
		  }
	}




	public function updateexpense($request)
	{
		DB::beginTransaction();
		try{
		$issue = false;
		$invoiceData = Order::where("invoice_number", $request->invoice_number)->first();
			$order = new ExpensesItems();

            
			
			$order->category_id    	= $request->expense_category;
            $order->expense_number    	= $request->expense_number;
            $order->original_invoice_number	    	= $request->original_invoice_number	;
            $order->payment_mode	    	= $request->payment_mode;
            $order->payment_mode_from	    	= $request->payment_mode_from;
			$order->order_date        	= $request->order_date;
            $order->address        	= $request->address;
            //$order->invoice_date        	= $request->invoice_date;
			$order->party_id          	= $request->party_id;
			$order->party_customer_id 	= $request->party_customer_id;
			$order->total_amount      	= ($request->total+$request->tax_amount);
			$order->tax_amount      	= ($request->tax_amount);
			$order->total_discount      = ($request->total_discount);
			$order->total_items      	= ($request->total_items);
			//$order->total_discount      	= ($request->total_discount);

		
			if($order->save())
			{
			}
			else{ 
				echo $issue= true; 
			}	
			// $sql = "update settings set recent_bill_number = '".($request->bill_number+1)."'  where setting_type = 'cr_number'";
			// 	DB::update($sql);

			// Get the items from the request
			$orderItems = $request->input('items');

			if ($order && !empty($orderItems)) {
				$total = 0;
				foreach ($orderItems as $item) { 
					// Check if item_id and item_name are present and valid
					if (!is_null($item['item_id']) && !is_null($item['single_unit_price']) && !is_null($item['quantity'])) {
						$quantity = !empty($item['quantity']) ? $item['quantity'] : 1;

						// Calculate the amount: single unit price * quantity
						$amount = $item['single_unit_price'] * $quantity;
						///$free = isset($item['free']) ? $item['free'] : 0;
							
						// Insert order items
						if(ExpensesItemDetalis::create([
							'user_id'            => auth('api')->user()->id,
							'order_id'           => $order->id,
							'product_id'        => (int)$item['item_id'],
							'quantity'           => $quantity,
							'unit_price'         => $item['single_unit_price'],
							'single_unit_price'  => $item['single_unit_price'],
							'free'              => $item['free'],
							//'tax_rate'           => 2,
							'discount_rate'      => $item['discount_rate'] ?? 0,
							'subtotal'           => $amount
						]))
						{
							$total = $total+$amount;
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
