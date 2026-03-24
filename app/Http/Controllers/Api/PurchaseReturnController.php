<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiBaseController;
use Illuminate\Http\Request;
use App\Models\PurchaseReturnDetalis;
use App\Models\PurchaseReturnitems;
use App\Models\LedgerModel;
use App\Models\LedgerCustomerModel;
use App\Http\Requests\Api\PurchaseReturn\IndexRequest;
use App\Http\Requests\Api\PurchaseReturn\StoreRequest;
use App\Http\Requests\Api\PurchaseReturn\UpdateRequest;
use App\Http\Requests\Api\PurchaseReturn\DeleteRequest;
use App\Http\Requests\Api\Sales\SalesCreateRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Order;
use \Mpdf\Mpdf as PDF;
use Illuminate\Support\Facades\Storage;

use App\Traits\OrderTraits;

class PurchaseReturnController extends ApiBaseController
{

    protected $model = PurchaseReturnDetalis::class;


	protected $indexRequest = IndexRequest::class;
	protected $storeRequest = StoreRequest::class;
	protected $updateRequest = UpdateRequest::class;
	protected $deleteRequest = DeleteRequest::class;

    public function salesPurchase( SalesCreateRequest $request)
	{
		DB::beginTransaction();
		try {

			// Create new Order instance
			if($request->selectedInvoice=="null")
			{
				if(!$this->createPurchaseReturn($request))
				{
					return response()->json(['message' => 'Purchase Return stored successfully.'], 201);
				}
			}
			else{
				if(!$this->updatepurchase($request))
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



	public function createPurchaseReturn($request)
	{
		DB::beginTransaction();
		try{
		$issue = false;
		//$invoiceData 	=	Order::where("invoice_number",$request->invoice_number)->first();
			$order = new PurchaseReturnDetalis();

			$order->dr_number    		= $request->bill_number;
			//$order->order_id     		=	$invoiceData->id;
			//$order->warehouse_id      = 1;
			$order->order_date        	= $request->order_date;
			$order->return_by        	 = auth('api')->user()->id;

			$order->party_id          	= $request->party_id;
			$order->party_customer_id 	= $request->party_customer_id;
			$order->total_amount      	= ($request->total+$request->tax_amount);
			$order->tax_amount      	= ($request->tax_amount);
			$order->total_discount      = ($request->total_discount);
			$order->total_items      	= ($request->total_items);


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
						$free = isset($item['free']) ? $item['free'] : 0;

						// Insert order items
						if(PurchaseReturnitems::create([
							'user_id'            => auth('api')->user()->id,
							'order_id'           => $order->id,
							'product_id'         => (int)$item['item_id'],
							'quantity'           => $quantity,
							'free'               => $free,
							'unit_price'         => $item['single_unit_price'],
							'single_unit_price'  => $item['single_unit_price'],
							'tax_rate'           => 2,
							'discount_rate'      => $item['discount_rate'] ?? 0,
							'subtotal'           => $amount,
							'cgst'               => isset($item['cgst']) ? $item['cgst'] : 0,
							'sgst'               => isset($item['sgst']) ? $item['sgst'] : 0
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
		catch (\Exception $e) { echo '444';
			DB::rollBack();

			Log::error('Error storing order: ' . $e->getMessage(), [
				'file' => $e->getFile(),
				'line' => $e->getLine(),
				'trace' => $e->getTraceAsString(),
			]);

			return response()->json(['message' => 'Error storing order, please try again.'], 500);
		}


	}


	public function getPurchaseReturnPdf(Request $request)
	{
		$documentFileName = "purchase_return_".$request->invoice.".pdf";

		$invoice_details 	= PurchaseReturnDetalis::where('dr_number',$request->invoice)->first();

		if (!$invoice_details) {
			return response()->json(['message' => 'Invoice not found: ' . $request->invoice], 404);
		}

		$customerDetails 	= LedgerModel::where('id',$invoice_details->party_id)->first();

		$partyDetails 		= LedgerCustomerModel::where('id',$invoice_details->party_customer_id)->first();

		$products 			= DB::select("SELECT A.quantity, A.single_unit_price, B.mrp, B.sale_rate, A.discount_rate, A.subtotal, B.hsn_sac, B.name, A.free , A.cgst as cgst_amount, A.sgst as sgst_amount FROM `purchase_return_item_details` A left join products B on B.id=A.product_id WHERE A.order_id=".$invoice_details->id);
			\Log::info('products', ['products' => $products]);
	   // Create the mPDF document
	   $document = new PDF( [
		   'mode' => 'utf-8',
		   'format' => [220, 196],
		   'margin_header' => '1',
		   'margin_top' => '10',
		   'margin_bottom' => '20',
		   'margin_footer' => '2',


	   ]);
	   $document->showWatermarkText = true;
	   $document->watermark_font = 'DejaVuSansCondensed';
		$document->watermarkTextAlpha = 0.03;
	   // Set some header informations for output
	   $header = [
		   'Content-Type' => 'application/pdf',
		   'Content-Disposition' => 'inline; filename="'.$documentFileName.'"'
	   ];
	   $document->SetWatermarkText($partyDetails ? $partyDetails->name : '', 3);
	   // Write some simple Content
	   $document->WriteHTML(view('purchaseReturnInvoice',[
		   'invoice_details' => $invoice_details,
		   'customer'=>$customerDetails,
		   'party'=>$partyDetails,
		   "products"=> $products
	   ]));
	  // $document->WriteHTML('<p>Write something, just for fun!</p>');

	   // Save PDF on your public storage
	   Storage::disk('file')->put($documentFileName, $document->Output($documentFileName, "S"));

	   // Get file back from storage with the give header informations
		Storage::disk('file')->download($documentFileName, 'Request', $header);

		$invoice_details->invoice_path = config('app.url') .'/'.$documentFileName;
		$invoice_details->save();
		echo config('app.url') .'/'.$documentFileName;
	}


	public function getPurchaseInvoiceDetails(Request $request)
	{
		try{
		$invoiceData 	=	PurchaseReturnDetalis::where("dr_number",$request->invoice)->first();
		//print_r($invoiceData);
		$customerData 	=	LedgerCustomerModel::where("id",$invoiceData->party_customer_id)->first();
		$invoiceItems	=	PurchaseReturnitems::where('order_id',$invoiceData->id)->get();
		// Ensure CGST and SGST are included and not zero if present in DB
		$invoiceItems = $invoiceItems->map(function($item) {
			$item->cgst = isset($item->cgst) ? (float)$item->cgst : 0;
			$item->sgst = isset($item->sgst) ? (float)$item->sgst : 0;
			return $item;
		});
		return response()->json([
			'message' => 'Data retrived successfulliedddd',
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


	public function updatepurchase($request)
	{
		DB::beginTransaction();
		try{
			$issue = false;

			$currentInvoice = $request->selectedInvoice !== "null"
				? $request->selectedInvoice
				: $request->bill_number;

			$order = PurchaseReturnDetalis::where('dr_number', $currentInvoice)->first();

			if (!$order) {
				DB::rollBack();
				return true;
			}

			$order->dr_number 			= $request->bill_number;
			$order->order_date 			= $request->order_date;
			$order->return_by 			= auth('api')->user()->id;
			$order->party_id 			= $request->party_id;
			$order->party_customer_id 	= $request->party_customer_id;
			$order->total_amount 		= ($request->total + $request->tax_amount);
			$order->tax_amount 			= $request->tax_amount;
			$order->total_discount 		= $request->total_discount;
			$order->total_items 		= $request->total_items;

			if (!$order->save()) {
				$issue = true;
			}

			// Rebuild linked child rows so header and items stay in sync after update.
			PurchaseReturnitems::where('order_id', $order->id)->delete();

			$orderItems = $request->input('items');

			if ($order && !empty($orderItems)) {
				foreach ($orderItems as $item) {
					if (!is_null($item['item_id']) && !is_null($item['single_unit_price']) && !is_null($item['quantity'])) {
						$quantity = !empty($item['quantity']) ? $item['quantity'] : 1;
						$amount = $item['single_unit_price'] * $quantity;
						$free = isset($item['free']) ? $item['free'] : 0;

						if(!PurchaseReturnitems::create([
							'user_id'            => auth('api')->user()->id,
							'order_id'           => $order->id,
							'product_id'         => (int)$item['item_id'],
							'quantity'           => $quantity,
							'free'               => $free,
							'unit_price'         => $item['single_unit_price'],
							'single_unit_price'  => $item['single_unit_price'],
							'tax_rate'           => 2,
							'discount_rate'      => $item['discount_rate'] ?? 0,
							'subtotal'           => $amount,
							'cgst'               => isset($item['cgst']) ? $item['cgst'] : 0,
							'sgst'               => isset($item['sgst']) ? $item['sgst'] : 0
						])) {
							$issue = true;
						}
					}
				}
			}

			DB::commit();
			return $issue;
		}
		catch(\Illuminate\Database\QueryException $ex){
			DB::rollback();
			return response()->json(['message' => $ex->getMessage()], 500);
			// Note any method of class PDOException can be called on $ex.
		  }
		catch (\Exception $e) {
			DB::rollBack();

			Log::error('Error storing order: ' . $e->getMessage(), [
				'file' => $e->getFile(),
				'line' => $e->getLine(),
				'trace' => $e->getTraceAsString(),
			]);

			return response()->json(['message' => 'Error storing order, please try again.'], 500);
		}


	}






}
