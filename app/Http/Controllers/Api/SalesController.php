<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiBaseController;
use App\Http\Requests\Api\Sales\IndexRequest;
use App\Http\Requests\Api\Sales\StoreRequest;
use App\Http\Requests\Api\Sales\UpdateRequest;
use App\Http\Requests\Api\Sales\DeleteRequest;
use App\Http\Requests\Api\Sales\SalesCreateRequest;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentModeModel;
use App\Models\PaymentMode;
use App\Models\LedgerModel;
use App\Models\LedgerCustomerModel;
use Illuminate\Support\Str;
use App\Traits\OrderTraits;
use \Mpdf\Mpdf as PDF; 
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SalesController extends ApiBaseController
{
	use OrderTraits;

	protected $model = Order::class;

	protected $indexRequest = IndexRequest::class;
	protected $storeRequest = StoreRequest::class;
	protected $updateRequest = UpdateRequest::class;
	protected $deleteRequest = DeleteRequest::class;

	public function __construct()
	{
		parent::__construct();

		$this->orderType = "sales";
	}

	public function salesCreate(SalesCreateRequest $request)
	{
		try {

			// Create new Order instance
			$order = new Order();
			$order->unique_id         = $this->generateUniqueId();
			$order->invoice_number    = $request->bill_number;
			$order->warehouse_id      = 1;
			$order->order_date        = date('Y-m-d');
			$order->user_id           = auth('api')->user()->id;
			$order->tax_rate          = 2; // Static tax rate; you might want to make this dynamic
			$order->tax_amount        = $request->tax_amount ?? 0.00;
			$order->discount          = $request->discount ?? 0.00;
			$order->subtotal          = $request->subtotal ?? 0.00;
			$order->total             = $request->total ?? 0.00;
			$order->due_amount        = $request->due_amount ?? 0.00;
			$order->order_status      = $request->order_status;
			$order->party_id          = $request->party_id;
			$order->party_customer_id = $request->party_customer_id;
			$order->address           = $request->address;

			if($order->save())
			{

				
			}	
			$sql = "update settings set recent_bill_number = '".($request->bill_number+1)."'  where setting_type = 'bill_number'";
				DB::update($sql);

			// Get the items from the request
			$orderItems = $request->input('items');

			if ($order && !empty($orderItems)) {
				foreach ($orderItems as $item) { 
					// Check if item_id and item_name are present and valid
					if (!is_null($item['item_id']) && !is_null($item['single_unit_price']) && !is_null($item['quantity'])) {
						$quantity = !empty($item['quantity']) ? $item['quantity'] : 1;

						// Calculate the amount: single unit price * quantity
						$amount = $item['single_unit_price'] * $quantity;
							
						// Insert order items
						OrderItem::create([
							'user_id'            => auth('api')->user()->id,
							'order_id'           => $order->id,
							'product_id'        => (int)$item['item_id'],
							'quantity'           => $quantity,
							'unit_price'         => $item['single_unit_price'],
							'single_unit_price'  => $item['single_unit_price'],
							'tax_rate'           => 2,
							'discount_rate'      => $item['discount_rate'] ?? 0,
							'subtotal'           => $amount
						]);
					}
				}
			}

			/*OrderItem::create([
				'company_id' => "1",
				'payment_id' => "1",
				'order_id '  => $order->id ?? 0,
				'amount'     => $request->subtotal ?? 0.00
			]);*/
			$listofpayment = PaymentMode::where('status','1')->get();
			foreach($listofpayment as $list):
				$payment = new PaymentModeModel();
				$payment->settlement_mode = $list->name;
				$payment->bill_number = $request->bill_number;
				$payment->order_id = $order->id;
				$payment->save();
			endforeach;


			return response()->json(['message' => 'Order and items stored successfully.'], 201);
		} catch (\Exception $e) {
			DB::rollBack();

			Log::error('Error storing order: ' . $e->getMessage(), [
				'file' => $e->getFile(),
				'line' => $e->getLine(),
				'trace' => $e->getTraceAsString(),
			]);

			return response()->json(['message' => 'Error storing order, please try again.'], 500);
		}
	}
	public function billNumber()
	{
		
		$bill = DB::select("select recent_bill_number from settings where setting_type='bill_number'");
		//print_r($bill);
		echo str_pad(($bill[0]->recent_bill_number+1),8,"0",STR_PAD_LEFT); ;
		
		
	}
	public function savepayment(Request $request)
	{
		//print_r($request->data);
		foreach($request->data as  $key => $value):
			if($value['bill_amount']!=null || $value['bill_amount']!="" || $value['bill_amount']>0){
			$newPay = PaymentModeModel::where('bill_number',$value['bill_number'])
			->where('settlement_mode',$value['settlement_mode'])
			->update([
				'bill_amount'=>$value['bill_amount'],
				'amount'=>$value['amount'],
				'remarks'=>$value['remarks'],
				'cash_tender'=>$value['cash_tender'],
				'cash_return'=>$value['cash_return']
			]);
			
			}
		endforeach;	
		return response()->json(['message' => 'Payment Saved successfully.'], 201);

	}


	function generateUniqueId($length = 20) 
	{
		$characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
		$charactersLength = strlen($characters);
		$randomString = '';

		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}

		return $randomString;
	}
	function getInvoicePdf(Request $request)
	{
		 $documentFileName = $request->invoice.".pdf";

		 $invoice_details 	= Order::where('invoice_number',$request->invoice)->get();
		 $customerDetails 	= LedgerModel::where('id',$invoice_details[0]->party_id)->get();
		 $partyDetails 		= LedgerCustomerModel::where('id',$invoice_details[0]->party_customer_id)->get();
		 
		 $products 			= DB::select("SELECT A.quantity,A.mrp,A.unit_price,A.discount_rate,A.subtotal,B.hsn_sac,B.name,(2.0) as sgst,(2.0) as igst FROM `order_items` A left join products B on B.id=A.product_id WHERE order_id=".$invoice_details[0]->id);
 
        // Create the mPDF document
        $document = new PDF( [
            'mode' => 'utf-8',
            'format' => [220, 196],
            'margin_header' => '1',
            'margin_top' => '10',
            'margin_bottom' => '20',
            'margin_footer' => '2',
        ]);     
 
        // Set some header informations for output
        $header = [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$documentFileName.'"'
        ];
		$document->watermark("your watermark text", 20, 10, 0.2);
        // Write some simple Content
        $document->WriteHTML(view('invoice',[
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

		 echo config('app.url') .'/'.$documentFileName;
		
		 
	}
}
