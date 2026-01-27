<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiBaseController;
use App\Http\Requests\Api\PurchaseReturn\IndexRequest;
use App\Http\Requests\Api\PurchaseReturn\StoreRequest;
use App\Http\Requests\Api\PurchaseReturn\UpdateRequest;
use App\Http\Requests\Api\PurchaseReturn\PurchaseCreateRequest;
use App\Models\PurchaseBillDetail;
use App\Models\PurchaseBillItemsDetail;
use App\Models\Order;
 use App\Models\DiscountModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\LedgerModel;
use App\Models\LedgerCustomerModel;
use App\Models\ShippingDetail;
use \Mpdf\Mpdf as PDF;
use Illuminate\Support\Facades\Storage;
class PurchaseBillController extends ApiBaseController
{

    protected $model = PurchaseBillDetail::class;
    protected $indexRequest = IndexRequest::class;
    protected $storeRequest = StoreRequest::class;
    protected $updateRequest = UpdateRequest::class;

    public function purchaseBillDetails( PurchaseCreateRequest $request)
	{
		DB::beginTransaction();
		try {

			// Create new Order instance
			if($request->selectedInvoice=="null")
			{
				if(!$this->createPurchaseBill($request))
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

//bill number //
public function billNumber()
{

	$bill = DB::select("select recent_bill_number from settings where setting_type='bill_number'");
	$discountItems = DiscountModel::where("status",'1')->get();
	//print_r($bill);
	//echo str_pad(($bill[0]->recent_bill_number+1),8,"0",STR_PAD_LEFT); ;
	return response()->json([
		'message' => 'Data retrived successfully',
		'data'=>["ref"=>str_pad(($bill[0]->recent_bill_number+1),8,"0",STR_PAD_LEFT),"discountItems"=>$discountItems]
	], 200);
}

// end bill number//



    public function createPurchaseBill($request)
	{
		DB::beginTransaction();
		try{
		$issue = false;
		$invoiceData = Order::where("invoice_number", $request->invoice_number)->first();
			$order = new PurchaseBillDetail();

			$order->invoice_number    	= $request->bill_number;
			//$order->order_id     		=	$invoiceData->id;
			$order->order_date        	= $request->order_date;
            $order->invoice_date        	= $request->invoice_date;
			$order->return_by        	 = auth('api')->user()->id;
			$order->party_id          	= $request->party_id;
			$order->party_customer_id 	= $request->party_customer_id;
			//$order->ledger_id          = $request->party_id;
			$order->total_amount      	= ($request->total);
			$order->due_amount      	= ($request->total);
			$order->tax_amount      	= ($request->tax_amount);
			$order->total_discount      = ($request->discount);
			$order->total_items      	= ($request->total_items);

			if($order->save())
			{


			}
			else{
				echo $issue= true;
			}
			$sql = "update settings set recent_bill_number = '".($request->bill_number+1)."'  where setting_type = 'cr_number'";
				DB::update($sql);
			$orderItems = $request->input('items');

			if ($order && !empty($orderItems)) {
				$total = 0;
				foreach ($orderItems as $item) {
					if (!is_null($item['item_id']) && !is_null($item['single_unit_price']) && !is_null($item['quantity'])) {
						$quantity = !empty($item['quantity']) ? $item['quantity'] : 1;
						$amount = $item['single_unit_price'] * $quantity;
						if(PurchaseBillItemsDetail::create([
							'user_id'            => auth('api')->user()->id,
							'order_id'           => $order->id,
							'product_id'        => (int)$item['item_id'],
							'quantity'           => $quantity,
							'free'              => $item['freeQty']==""?0:$item['freeQty'],
							'unit_price'         => $item['single_unit_price'],
							'single_unit_price'  => $item['single_unit_price'],
							'discount_type_id'      => $item['discount_type_id'] ?? 0,
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
			DB::rollback();
			echo  response()->json(['message' => $ex->getMessage()], 500);
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


	public function geBillInvoicePdf(Request $request)
	{
		$documentFileName = "purchase_bill_".$request->invoice.".pdf";

		// $invoice_details 	= PurchaseBillDetail::where('invoice_number',$request->invoice)->first();
		// $customerDetails 	= LedgerModel::where('id',$invoice_details->party_id)->get();

		// $partyDetails 		= LedgerCustomerModel::where('id',$invoice_details->party_customer_id)->get();

		// $products 			= DB::select("SELECT A.quantity,A.single_unit_price,B.mrp,B.sale_rate,A.discount_rate,A.subtotal,B.hsn_sac,B.name,C.cgst as cgst_amount,C.sgst as sgst_amount FROM `purchase_bill_items_details` A left join products B on B.id=A.product_id left join tax_catagories C on C.id=B.tax_category WHERE order_id=".$invoice_details->id);

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
	  // $document->SetWatermarkText($partyDetails[0]->name, 3);
	   // Write some simple Content
	//    $document->WriteHTML(view('billReturnInvoice',[
	// 	//    'invoice_details' => $invoice_details,
	// 	//    'customer'=>$customerDetails,
	// 	//    'party'=>$partyDetails,
	// 	//    "products"=> $products
	//    ]));
	   $document->WriteHTML('<p>Write something, just for fun!</p>');

	   // Save PDF on your public storage
	   Storage::disk('file')->put($documentFileName, $document->Output($documentFileName, "S"));

	   // Get file back from storage with the give header informations
		Storage::disk('file')->download($documentFileName, 'Request', $header);

		// $invoice_details->invoice_path = config('app.url') .'/'.$documentFileName;
		// $invoice_details->save();
		echo config('app.url') .'/'.$documentFileName;
	}



	public function getBillInvoiceDetails(Request $request)
	{
		try{

		$invoiceData 	=	PurchaseBillDetail::where("invoice_number",$request->invoice)->first();
		$invoiceItems	=	PurchaseBillItemsDetail::where('order_id',$invoiceData->id)->get();
		$partyDetails	=	LedgerModel::where('id',$invoiceData->ledger_id)->first();
		return response()->json([
			'message' => 'Data retrived successfullt',
			'data'=>["invoiceData"=>$invoiceData,"invoiceItems"=>$invoiceItems,"partyDetails"=>$partyDetails]
		], 200);
		}
		catch(\Exception $ex)
		{
			return response()->json(['message' => $ex->getMessage()], 500);
		}
		catch(\Illuminate\Database\QueryException $ex){
			return response()->json(['message' => $ex->getMessage()], 500);

		  }
	}



public function invoiceNumber(Request $request)
{
    //$customer = [];
    $invoiceItems = [];
    $cr = DB::select(query: "SELECT recent_bill_number FROM settings WHERE setting_type='cr_number'");
    $cr = str_pad(($cr[0]->recent_bill_number + 1), 8, "0", STR_PAD_LEFT);
    $recentBill = PurchaseBillDetail::select(['pbd.id','total_amount','invoice_number','tax_amount',
        DB::raw('DATE_FORMAT(order_date, "%d-%m-%Y") as invoiceDate'),
        DB::raw('COUNT(pbi.product_id) as totalProducts')])->from('purchase_bill_details as pbd')->join('purchase_bill_items_details as pbi', 'pbi.order_id', '=', 'pbd.id')
    ->where('party_id', $request->party_id)->groupBy('pbd.id')->orderBy('pbd.id', 'DESC')->get()->take(10);

     if (count($recentBill) > 0) {
      //  $customer = LedgerCustomerModel::select(['id', 'cus_name', 'mobile_number', 'address'])->where("id", $recentBill[0]->party_customer_id)->first();
        $invoiceItems = PurchaseBillItemsDetail::where('order_id', $recentBill[0]->id)->get();
    }
    return response()->json([
        'message' => 'Purchase Return Data retrieved successfully',
        'data' => [
            "cr" => $cr,
            "recentBill" => $recentBill,
           // "customer" => $customer,
            "invoiceItems" => $invoiceItems
        ]
    ], 200);
}



    public function getInvoiceItem(Request $request)
    {
        $invoiceItems = PurchaseBillItemsDetail::join('purchase_bill_details', 'purchase_bill_details.id', '=', 'purchase_bill_items_details.order_id')
        ->whereIn('purchase_bill_details.id', explode(",", $request->id))
        ->get();
            return response()->json([
                'message' => 'Return Data retrived successfully',
                'data'=>["invoiceItems"=>$invoiceItems]
            ], 200);
    }


//update///

public function updatebill($request)
	{
		DB::beginTransaction();
		try{
		$issue = false;
		$invoiceData = Order::where("invoice_number", $request->invoice_number)->first();
			$order = new PurchaseBillDetail();

			$order->invoice_number    	= $request->bill_number;
			//$order->order_id     		=	$invoiceData->id;
			$order->order_date        	= $request->order_date;
            $order->invoice_date        	= $request->invoice_date;
			$order->return_by        	 = auth('api')->user()->id;
			$order->party_id          	= $request->party_id;
			$order->total_amount      	= ($request->total);
			$order->tax_amount      	= ($request->tax_amount);
			$order->due_amount      	= ($request->total);
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
						///$free = isset($item['free']) ? $item['free'] : 0;

						// Insert order items
						if(PurchaseBillItemsDetail::create([
							'user_id'            => auth('api')->user()->id,
							'order_id'           => $order->id,
							'product_id'        => (int)$item['item_id'],
							'quantity'           => $quantity,
							'free'              => $item['free'],
							'unit_price'         => $item['single_unit_price'],
							'single_unit_price'  => $item['single_unit_price'],
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


/// end of upadate//




}
