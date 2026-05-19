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
use App\Models\Receipt;
use App\Models\ReceiptBank;
use App\Models\LedgerModel;
use App\Models\ShippingDetail;
 use App\Models\DiscountModel;
use App\Models\SalesReturn;
use App\Models\SalesReturnItems;
use App\Models\LedgerCustomerModel;
use App\Models\ReturnReasonModel;
use Illuminate\Support\Str;
use App\Traits\OrderTraits;
use \Mpdf\Mpdf as PDF;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Examyou\RestAPI\Exceptions\ApiException;

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

	protected function normalizeProductId($value)
	{
		if ($value === null || $value === '') {
			return null;
		}

		if (is_numeric($value)) {
			return (int) $value;
		}

		$decodedId = $this->getIdFromHash($value);

		return $decodedId ? (int) $decodedId : null;
	}

	protected function resolveLoginUserId($value = null)
	{
		$authUserId = auth('api')->user() ? auth('api')->user()->id : null;
		$loggedUser = auth('api')->user();

		if (
			$loggedUser &&
			(
				(method_exists($loggedUser, 'hasRole') && $loggedUser->hasRole('salesman')) ||
				($loggedUser->user_type === 'staff_members' && $loggedUser->role && $loggedUser->role->name === 'salesman')
			)
		) {
			return $authUserId;
		}

		if ($value === null || $value === '') {
			return $authUserId;
		}

		if (is_numeric($value)) {
			return (int) $value;
		}

		$decodedId = $this->getIdFromHash($value);

		return $decodedId ? (int) $decodedId : $authUserId;
	}

	public function salesCreate(SalesCreateRequest $request)
	{
		//DB::beginTransaction();
		try {

			// Create new Order instance
			if($request->selectedInvoice==null || $request->selectedInvoice=="null")
			{
				if($this->createOrder($request,"sales"))
				{
					return response()->json(['message' => 'Order and items stored successfully.'], 201);
				}
			}
			else{
				if($this->updateOrder($request,"sales"))
				{
					return response()->json(['message' => 'Order and items updated successfully.'], 201);
				}
			}
			//DB::commit();
		}
		catch(\Illuminate\Database\QueryException $ex){
			//dd($ex->getMessage());
			//DB::rollback();
			return response()->json(['message' => $ex->getMessage()], 500);
			// Note any method of class PDOException can be called on $ex.
		  }
		catch (\Exception $e) {
			//DB::rollBack();

			Log::error('Error storing order: ' . $e->getMessage(), [
				'file' => $e->getFile(),
				'line' => $e->getLine(),
				'trace' => $e->getTraceAsString(),
			]);

			return response()->json(['message' => 'Error storing order, please try again.'], 500);
		}
	}
	public function createOrder($request,$type)
	{
			//DB::beginTransaction();
			$issue = false;
			$loginUserId = $this->resolveLoginUserId($request->login_user_id ?? null);
			$order = new Order();
			$order->unique_id         = $this->generateUniqueId();
			$order['invoice_number']    = ($type=="sales"?$request->bill_number:"QT-".$request->invoice_number);
			$order->warehouse_id      = 1;
			$order->order_date        = date('Y-m-d');
			$order->user_id           = $loginUserId;
			$order->tax_rate          = 2;
			$order->order_type          = $type;

			$order->discount    = $request->discount ?? 0.00;
			$order->subtotal          = $request->subtotal ?? 0.00;
			$order->total             = $request->total ?? 0.00;
			$order->due_amount        = $order->total;
			$order->order_status      = $request->order_status;
			$order->party_id          = $request->party_id;
			$order->ledger_id          = $request->party_id;
			$order->party_customer_id = $request->party_customer_id;
            $order->party_shippingaddress_id = $request->party_shippingaddress_id;
            $order->address           = $request->address;
			$order->total      	= ($request->subtotal+$request->tax_amount)-($request->discount);
			$order->tax_amount      	= ($request->tax_amount);

			$order->total_items      	= ($request->total_items);
			
			 
			if($order->save())
			{


			}
			else{
				
				$issue= true;
			}
			if($type=="sales"){
			$sql = "update settings set recent_bill_number = '".($request->bill_number+1)."'  where setting_type = 'bill_number'";

			}
			else
			{
				$sql = "update settings set recent_bill_number = '".($request->invoice_number+1)."'  where setting_type = 'quotation_number'";


			}
			DB::update($sql);

			// Get the items from the request
			$orderItems = $request->input('items');

			if ($order && !empty($orderItems)) {
				foreach ($orderItems as $item) {
					// Check if item_id and item_name are present and valid
					if (!is_null($item['item_id']) && !is_null($item['single_unit_price']) && !is_null($item['quantity'])) {
						$productId = $this->normalizeProductId($item['item_id']);
						if (!$productId) {
							continue;
						}
						$quantity = !empty($item['quantity']) ? $item['quantity'] : 1;

						// Calculate the amount: single unit price * quantity
						$amount = $item['single_unit_price'] * $quantity;

						// Insert order items
						OrderItem::create([
							'user_id'            => $loginUserId,
							'order_id'           => $order->id,
							'product_id'        => $productId,
							'quantity'           => $quantity,
							'freeQty'           =>  $item['freeQty']>0?$item['freeQty']:0,
							'unit_price'         => $item['single_unit_price'],
							'single_unit_price'  => $item['single_unit_price'],
							'tax_rate'           => 2,
							'discount_rate'      => $item['discount_rate'] ?? 0,
							'discount_type_id'      => $item['discount_type_id'] ?? 0,
							'subtotal'           => $amount
						]);

						// Reduce stock in ProductDetails for sales, increase for returns
						$productDetailsList = \App\Models\ProductDetails::where('product_id', $productId)->get();
						foreach ($productDetailsList as $productDetails) {
							if ($type === 'sales') {
								$productDetails->current_stock = max(0, $productDetails->current_stock - $quantity);
							} else {
								$productDetails->current_stock += $quantity;
							}
							$productDetails->save();
						}
						// Always recalculate products.current_stock as sum of all product_details.current_stock
						$totalCurrentStock = \App\Models\ProductDetails::where('product_id', $productId)->sum('current_stock');
						$product = \App\Models\Product::find($productId);
						if ($product) {
							$product->current_stock = $totalCurrentStock;
							$product->save();
						}
					}	
				}
			}
			if($type=="sales"){
			$listofpayment = ReceiptBank::where('is_delete',0)->get();
			foreach($listofpayment as $list):
				/*$payment = new PaymentModeModel();
				$payment->settlement_mode = $list->name;
				$payment->bill_number = $request->bill_number;
				$payment->order_id = $order->id;*/
				$insert = DB::table('payment_mode')->insert([
						"settlement_mode" =>$list->bank_name,
						"settlement_id" =>$list->id,
						"bill_number" => $request->bill_number,
						"order_id" => $order->id,
						"login_user_id" => $loginUserId,
					]);
					
			endforeach;
		}	
			return $issue;
	}
	public function updateOrder($request,$type)
	{
			$issue = false;
			$loginUserId = $this->resolveLoginUserId($request->login_user_id ?? null);
			$orderQuery = Order::where("invoice_number",$request->selectedInvoice);
			$order = $this->applyLoggedUserScope($orderQuery, 'orders', 'user_id')->first();

			if (!$order) {
				throw new ApiException('You can access only your own sales data.');
			}

			$order->warehouse_id      = 1;
			$order->order_date        = $request->order_date;
			$order->user_id           = $loginUserId;
			$order->tax_rate          = 2; // Static tax rate; you might want to make this dynamic
			$order->tax_amount        = $request->tax_amount ?? 0.00;
			$order->discount          = $request->discount ?? 0.00;
			$order->subtotal          = $request->subtotal ?? 0.00;
			$order->total             = $request->total ?? 0.00;
			$order->due_amount        = $order->total;
			$order->order_status      = $request->order_status;
			$order->party_id          = $request->party_id;
			$order->ledger_id          = $request->party_id;
			$order->party_customer_id = $request->party_customer_id;
            $order->party_shippingaddress_id = $request->party_shippingaddress_id;
			$order->address           = $request->address;
			$order->order_type          = $type;

			if($order->save())
			{
			}
			else{
				$issue= true;
			}

			// Get the items from the request
			OrderItem::where("order_id",$order->id)->delete();
			//PaymentModeModel::where("order_id",$order->id)->delete();
			$orderItems = $request->input('items');


			if ($order && !empty($orderItems)) {
				foreach ($orderItems as $item) {
					// Check if item_id and item_name are present and valid
					if (!is_null($item['item_id']) && !is_null($item['single_unit_price']) && !is_null($item['quantity'])) {
						$productId = $this->normalizeProductId($item['item_id']);
						if (!$productId) {
							continue;
						}
						$quantity = !empty($item['quantity']) ? $item['quantity'] : 1;
						// Calculate the amount: single unit price * quantity
						$amount = $item['single_unit_price'] * $quantity;
						// Insert order items
						OrderItem::create([
							'user_id'            => $loginUserId,
							'order_id'           => $order->id,
							'product_id'        => $productId,
							'quantity'           => $quantity,
							'freeQty'           =>  $item['freeQty']>0?$item['freeQty']:0,
							'unit_price'         => $item['single_unit_price'],
							'single_unit_price'  => $item['single_unit_price'],
							'tax_rate'           => 2,
							'discount_rate'      => $item['discount_rate'] ?? 0,
							'discount_type_id'   => $item['discount_type_id'] ,
							'subtotal'           => $amount
						]);

						// Reduce stock in ProductDetails
						$productDetails = \App\Models\ProductDetails::where('product_id', $productId)->first();
						if ($productDetails) {
							$productDetails->current_stock = max(0, $productDetails->current_stock - $quantity);
							$productDetails->save();
						}
					}
				}
			}

			return $issue;
	}
	public function billNumber(Request $request)
	{

		$bill = DB::select("select recent_bill_number from settings where setting_type='bill_number'");
		$discountItems = DiscountModel::where("status",'1')->get();
		$returnTypes = ReturnReasonModel::where("status",'1')->get();
		if(isset($request->party_id))
		$customerDetails 	= LedgerModel::select('opening_balance')->where('id',$request->party_id)->first();
		//print_r($bill);
		//echo str_pad(($bill[0]->recent_bill_number+1),8,"0",STR_PAD_LEFT); ;
		return response()->json([
			'message' => 'Data retrived successfully',
			'data'=>["ref"=> str_pad(($bill[0]->recent_bill_number+1),8,"0",STR_PAD_LEFT),
			"discountItems"=>$discountItems,
			"returnTypes"=>$returnTypes,
			"balance"=>$request->party_id?$customerDetails->opening_balance:""]
		], 200);
	}
	public function crNumber(Request $request)
	{
		$customer =[];
		$invoiceItems=[];
		$cr = DB::select("select recent_bill_number from settings where setting_type='cr_number'");
		$cr = str_pad(($cr[0]->recent_bill_number+1),8,"0",STR_PAD_LEFT);
		$recentBill	= Order::select(['due_amount',"orders.id","party_customer_id",'invoice_number','total','tax_amount',DB::raw('DATE_FORMAT(order_date, "%d-%m-%Y") as invoiceDate'),DB::raw('count(order_items.product_id) as totalProducts')])
			->where('party_id',$request->party_id)
			->where('order_type','sales')
			->where('total','>',0)
			->join('order_items', 'order_items.order_id', '=', 'orders.id');
		$recentBill = $this->applyLoggedUserScope($recentBill, 'orders', 'user_id')
			->groupBy('order_items.order_id')
			->orderBy('orders.id','DESC')
			->get()
			->take(10);
		if(count($recentBill)>0){
		$customer = LedgerCustomerModel::select(['id','cus_name','mobile_number','address'])->where("id",$recentBill[0]->party_customer_id)->first();
		$invoiceItems	=	OrderItem::where('order_id',$recentBill[0]->id)->get();
		}

		return response()->json([
			'message' => 'Data retrived successfully',
			'data'=>["cr"=>$cr,"recentBill"=>$recentBill,"customer"=>$customer,"invoiceItems"=>$invoiceItems]
		], 200);
	}

	public function getInvoiceItems(Request $request)
	{
			$invoiceItems = OrderItem::query()
				->join('orders', 'orders.id', '=', 'order_items.order_id')
				->whereIn('order_items.order_id', explode(",", $request->id))
				->select([
					'order_items.*',
					'orders.invoice_number as invoice_number',
					'orders.invoice_type as invoice_type',
					'orders.order_type as order_type',
					'orders.order_date as order_date',
					'orders.warehouse_id as warehouse_id',
					'orders.from_warehouse_id as from_warehouse_id',
					'orders.tax_amount as tax_amount',
					'orders.discount as discount',
					'orders.shipping as shipping',
					'orders.total as total',
					'orders.paid_amount as paid_amount',
					'orders.due_amount as due_amount',
					'orders.order_status as order_status',
					'orders.notes as notes',
					'orders.document as document',
					'orders.staff_user_id as staff_user_id',
					'orders.payment_status as payment_status',
					'orders.total_items as total_items',
					'orders.total_quantity as total_quantity',
					'orders.terms_condition as terms_condition',
					'orders.is_deletable as is_deletable',
					'orders.cancelled as cancelled',
					'orders.cancelled_by as cancelled_by',
					'orders.customer_id as customer_id',
					'orders.party_id as party_id',
					'orders.party_customer_id as party_customer_id',
					'orders.bill_number as bill_number',
					'orders.address as address',
					'orders.payment_id as payment_id',
					'orders.invoice_path as invoice_path',
					'orders.party_name as party_name',
					'orders.ledger_id as ledger_id',
					'orders.party_shippingaddress_id as party_shippingaddress_id',
					'orders.party_address_id as party_address_id',
					'orders.unique_id as order_unique_id',
				]);
			$invoiceItems = $this->applyLoggedUserScope($invoiceItems, 'orders', 'user_id')->get();
			return response()->json([
				'message' => 'Data retrived successfully',
				'data'=>["invoiceItems"=>$invoiceItems]
			], 200);
	}

	public function savepayment(Request $request)
{
    DB::beginTransaction();

    try {
        $totalAmount = 0;
        $totalBillAmount = (float) round($request->data[0]['bill_amount'], 2);
        $currentRequestTranxNumber = null;

        foreach ($request->data as $key => $value) {
            if ($value['bill_amount'] != null && $value['bill_amount'] != "" && $value['amount'] > 0) {
                $balanceAdjusted = $value['bill_amount'] - $value['amount'];
                $paymentModeRow = PaymentModeModel::where('bill_number', $value['bill_number'])
                    ->where('settlement_mode', $value['settlement_mode'])
                    ->first();

                $tranxNumber = $paymentModeRow && !empty($paymentModeRow->tranx_number)
                    ? $paymentModeRow->tranx_number
                    : null;

                if (!$tranxNumber) {
                    if ($currentRequestTranxNumber === null) {
                        $currentRequestTranxNumber = $this->formatPaymentTranxNumber($this->getNextPaymentTranxSequence());
                    }

                    $tranxNumber = $currentRequestTranxNumber;
                }

                PaymentModeModel::where('bill_number', $value['bill_number'])
                    ->where('settlement_mode', $value['settlement_mode'])
                    ->update([
                        'bill_amount' => $value['bill_amount'],
                        'amount' => $value['amount'],
                        'remarks' => $value['remarks'],
                        'cash_tender' => $value['cash_tender'],
                        'cash_return' => $value['cash_return'],
						'payment_type' => "sales",
                        'balance_adjusted' => $balanceAdjusted,
                        'tranx_number' => $tranxNumber,
                        'login_user_id' => $this->resolveLoginUserId($request->login_user_id ?? null),
                    ]);

                $totalAmount += $value['amount'];
			    $this->createReciept($value);
            }
        }

        $totalAmount = round($totalAmount, 2);
        $invoice_number = isset($request->data[0]['invoice_number']) ? $request->data[0]['invoice_number'] : $request->data[0]['bill_number'];

        if ($totalAmount >= $totalBillAmount) {
            Order::where('invoice_number', $invoice_number)
                ->update(['payment_status' => 'Paid', 'due_amount' => 0]);
        } elseif ($totalAmount > 0) {
            Order::where('invoice_number', $invoice_number)
                ->update(['payment_status' => 'Partially paid', 'due_amount' => ($totalBillAmount - $totalAmount)]);
        }

        DB::commit();

        return response()->json(['message' => 'Sales Entry Saved successfully.'], 201);
    } catch (\Exception $e) {
        DB::rollBack();

        Log::error('Error saving sales payment', [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
        ]);

        return response()->json(['message' => 'Unable to save payment details.'], 500);
    }
}

	protected function getNextPaymentTranxSequence(): int
	{
		$maxSequence = PaymentModeModel::whereNotNull('tranx_number')
			->where('tranx_number', 'like', 'PAY-%')
			->selectRaw("MAX(CAST(SUBSTRING(tranx_number, 5) AS UNSIGNED)) as max_sequence")
			->value('max_sequence');

		if (!$maxSequence) {
			return 1;
		}

		return ((int) $maxSequence) + 1;
	}

	protected function formatPaymentTranxNumber(int $sequence): string
	{
		return 'PAY-' . str_pad((string) $sequence, 4, '0', STR_PAD_LEFT);
	}
public function createReciept($payment)
{
		$order = DB::select("select party_id, order_date from orders where invoice_number='".$payment['bill_number']."'");
		//Order::where("invoice_number",$payment['bill_number'])->get();
		//dd($order[0]->party_id);
		if (empty($order)) {
			return; // Exit if order not found
		}
		
		$cr = DB::select("select recent_bill_number from settings where setting_type='voucher_number'");
		if (empty($cr)) {
			return; // Exit if settings not found
		}
		
            $subtotal = 0;
            $short_subtotal = 0;
            $create= Receipt::create([
                "voucher_number"=>str_pad(((int)$cr[0]->recent_bill_number)+1,8,"0",STR_PAD_LEFT),
                "receipt_mode" =>$payment['settlement_id'],               
                "order_date" =>$order[0]->order_date,
                "party_id" => $order[0]->party_id,                
                "receipt_date" => date('Y-m-d h:i:s') ,               
                "short_amount" => 0,
                "amount" =>$payment['amount'],
                "subtotal" => $subtotal,
                "short_subtotal" => $short_subtotal
                ]);
				$sql = "update settings set recent_bill_number = '".($cr[0]->recent_bill_number+1)."'  where setting_type = 'voucher_number'";
            	DB::update($sql);

}

	public function quotationCreate(Request $request)
	{
		DB::beginTransaction();
		try {

			// Create new Order instance
			if($request->selectedInvoice==null || $request->selectedInvoice=="null")
			{
				if($this->createOrder($request,"quotation"))
				{
					return response()->json(['message' => 'Order and items stored successfully.'], 201);
				}
			}
			else{
				if(!$this->updateOrder($request,"quotation"))
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

		 $invoice_details 	= Order::where('invoice_number',$request->invoice)->first();

		 $partyDetails 		= LedgerCustomerModel::where('id', $invoice_details->party_customer_id)->get();
		 $customerDetails 	= LedgerModel::where('id',$invoice_details->ledger_id)->get();
		$company = \App\Models\Company::first(); 


		 $products 			= DB::select("SELECT A.quantity,A.freeqty,B.mrp,B.sale_rate,A.discount_rate,A.discount_type_id,A.subtotal,B.hsn_sac,B.name,H.cgst as cgst, H.sgst as sgst, H.lgst as igst, H.cess as cess FROM `order_items` A left join products B on B.id=A.product_id left join hsc_sac H on H.id=B.hsn_sac WHERE order_id=".$invoice_details->id);
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
        $document->WriteHTML(view('invoice', [
            'invoice_details' => $invoice_details,
            'customer'=>$customerDetails,
            'party'=>$partyDetails,
            'products'=> $products,
			'company'=>$company
        ]));
       // $document->WriteHTML('<p>Write something, just for fun!</p>');

        // Save PDF on your public storage
        Storage::disk('file')->put($documentFileName, $document->Output($documentFileName, "S"));

        // Get file back from storage with the give header informations
         Storage::disk('file')->download($documentFileName, 'Request', $header);
		 $invoice_details->invoice_path = config('app.url') .'/'.$documentFileName;
		 $invoice_details->save();
		 echo config('app.url') .'/'.$documentFileName;
	\Log::info('products', ['products' => $products]);
	//  \Log::info('invoice_details', ['invoice_details' => $invoice_details]);
	//  \Log::info('partyDetails', ['partyDetails' => $partyDetails]);
	//  \Log::info('customerDetails', ['customerDetails' => $customerDetails]);



	}
	public function getInvoiceDetails(Request $request)
	{

		try{
		$invoiceDataQuery 	=	Order::where("invoice_number",$request->invoice);
		$invoiceData = $this->applyLoggedUserScope($invoiceDataQuery, 'orders', 'user_id')->first();

		if (!$invoiceData) {
			return response()->json(['message' => 'Invoice not found'], 404);
		}

		$customerData 	=	LedgerCustomerModel::where("id",$invoiceData->party_customer_id)->first();
        $shipppingaddressData 	=	ShippingDetail::where("id",$invoiceData->party_shippingaddress_id)->first();

		$partyDetails	=	LedgerModel::where('id',$invoiceData->ledger_id)->first();
		$invoiceItems	=	OrderItem::where('order_id',$invoiceData->id)->get();
		$shippingDetails	=	ShippingDetail::where('id',$invoiceData->ledger_id)->first();
		return response()->json([
			'message' => 'Data retrived successfully',
			'data'=>["invoiceData"=>$invoiceData,"customerData"=>$customerData,
			"invoiceItems"=>$invoiceItems,
            "shipppingaddressData"=>$shipppingaddressData,
			"partyDetails"=>$partyDetails,
			"shippingDetails"=>$shippingDetails,

			]
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
	public function getReturnInvoiceDetails(Request $request)
	{

		try{
		$discountItems = DiscountModel::where("status",'1')->get();
		$returnTypes 	= ReturnReasonModel::where("status",'1')->get();
		$invoiceDataQuery 	=	SalesReturn::where("cr_number",$request->invoice);
		$invoiceData = $this->applyLoggedUserScope($invoiceDataQuery, 'sales_return', 'return_by')->first();

		if (!$invoiceData) {
			return response()->json(['message' => 'Invoice not found'], 404);
		}

		$customerData 	=	LedgerCustomerModel::where("id",$invoiceData->party_customer_id)->first();
		$partyDetails	=	LedgerModel::where('id',$customerData->ledger_id)->first();
		$invoiceItems	=	SalesReturnItems::where('order_id',$invoiceData->id)->get();
		return response()->json([
			'message' => 'Data retrived successfully',
			'data'=>["invoiceData"=>$invoiceData,"customerData"=>$customerData,
			"invoiceItems"=>$invoiceItems,
			"partyDetails"=>$partyDetails,
			"discountItems"=>$discountItems,
			"returnTypes"=>$returnTypes
			]
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
	public function salesReturn(SalesCreateRequest $request)
	{


			// Create new Order instance
			if($request->selectedInvoice=="null" || $request->selectedInvoice==null)
			{
				if(!$this->createSalesReturn($request))
				{
					return response()->json(['message' => 'Order and items stored successfully.'], 201);
				}
			}
			else{
				if(!$this->updateSalesReturn($request))
				{
					return response()->json(['message' => 'Order and items updated successfully.'], 201);
				}
			}



	}
	public function createSalesReturn($request)
	{
		DB::beginTransaction();
		try{
		$issue = false;
		$loginUserId = $this->resolveLoginUserId($request->login_user_id ?? null);
		$invoiceData 	=	Order::where("invoice_number",$request['invoice_number']!=""?$request['invoice_number']:$request['bill_number'])->first();
			$order = new SalesReturn();

			$order->cr_number    		= $request->bill_number;
			$order->order_id     		=	$invoiceData->id;
			//$order->warehouse_id      = 1;
			$order->order_date        	= $request->order_date;
			$order->return_by        	 = $loginUserId;
			$order->login_user_id      = $loginUserId;

			$order->party_id          	= $request->party_id;
			$order->party_customer_id 	= $request->party_customer_id;
			$order->total_amount      	= ($request->total);
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

			// Get the items from the request
			$orderItems = $request->input('items');

			if ($order && !empty($orderItems)) {
				$total = 0;
				foreach ($orderItems as $item) {
					// Check if item_id and item_name are present and valid
					if ( $item['return_qty']>0 && !is_null($item['item_id']) && !is_null($item['single_unit_price']) && !is_null($item['quantity'])) {
						$productId = $this->normalizeProductId($item['item_id']);
						if (!$productId) {
							continue;
						}
						$quantity = !empty($item['return_qty']) ? $item['return_qty'] : 0;

						// Calculate the amount: single unit price * quantity
						$amount = $item['single_unit_price'] * $quantity;

						// Insert order items
						if(SalesReturnItems::create([
							'user_id'            => $loginUserId,
							'order_id'           => $order->id,
							'product_id'        => $productId,
							'quantity'           => $item['quantity'],
							'return_qty'           => $item['return_qty'],
							'unit_price'         => $item['single_unit_price'],
							'single_unit_price'  => $item['single_unit_price'],
							'tax_rate'           => 2,
							'disc_value'        => $item['discount_rate'] ?? 0,
							'subtotal'           => $amount,
							'return_invoice'           => $item['invoice'] ?? 0,
							'disc_type'         => $item['discount_type_id'] ?? 0,
							'return_reason_code'=> $item['return_reason_code'],
							'invoice_date' => isset($item['invoice_date']) ? date('d-m-Y', strtotime($item['invoice_date'])) : null,
							'cgst' => $item['cgst'] ?? 0,
							'sgst' => $item['sgst'] ?? 0,
							'igst' => $item['igst'] ?? 0,
							'cess' => $item['cess'] ?? 0
						]))
						{
							$total = $total+$amount;
							// Update current_stock in ProductDetails
							$productDetails = \App\Models\ProductDetails::where('product_id', $productId)->first();
							$productDetailsList = \App\Models\ProductDetails::where('product_id', $productId)->get();
							foreach ($productDetailsList as $productDetails) {
								$productDetails->current_stock += $item['return_qty'] ?? $quantity;
								$productDetails->save();
							}
							// Always recalculate products.current_stock as sum of all product_details.current_stock
							$totalCurrentStock = \App\Models\ProductDetails::where('product_id', $productId)->sum('current_stock');
							$product = \App\Models\Product::find($productId);
							if ($product) {
								$product->current_stock = $totalCurrentStock;
								$product->save();
							}
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
		catch (\Exception $e) { echo '444'.$e->getMessage();
			DB::rollBack();

			Log::error('Error storing order: ' . $e->getMessage(), [
				'file' => $e->getFile(),
				'line' => $e->getLine(),
				'trace' => $e->getTraceAsString(),
			]);

			return response()->json(['message' => 'Error storing order, please try again.'], 500);
		}

	}public function updateSalesReturn($request)
	{
		DB::beginTransaction();
		try{
		$issue = false;
		$loginUserId = $this->resolveLoginUserId($request->login_user_id ?? null);
		$invoiceData 	=	SalesReturn::where("cr_number",$request['bill_number'])->first();

			$order = new SalesReturn();

			$invoiceData->cr_number    		= $request->bill_number;
			//$invoiceData->order_id     		=	$invoiceData->id;
			//$order->warehouse_id      = 1;
			$invoiceData->order_date        	= $request->order_date;
			$invoiceData->return_by        	 = $loginUserId;
			$invoiceData->login_user_id      = $loginUserId;

			$invoiceData->party_id          	= $request->party_id;
			$invoiceData->party_customer_id 	= $request->party_customer_id;
			$invoiceData->total_amount      	= ($request->total);
			$invoiceData->tax_amount      	= ($request->tax_amount);
			$invoiceData->total_discount      = ($request->discount);
			$invoiceData->total_items      	= ($request->total_items);


			if($invoiceData->save())
			{


			}
			else{
				echo $issue= true;
			}
			/*$sql = "update settings set recent_bill_number = '".($request->bill_number+1)."'  where setting_type = 'cr_number'";
				DB::update($sql);*/

			// Get the items from the request
			$orderItems = $request->input('items');

			if ($order && !empty($orderItems)) {
				SalesReturnItems::where('order_id',$invoiceData->id )->delete();
				$total = 0;
				foreach ($orderItems as $item) {
					// Check if item_id and item_name are present and valid
					if ( $item['return_qty']>0 && !is_null($item['item_id']) && !is_null($item['single_unit_price']) && !is_null($item['quantity'])) {
						$productId = $this->normalizeProductId($item['item_id']);
						if (!$productId) {
							continue;
						}
						$quantity = !empty($item['return_qty']) ? $item['return_qty'] : 0;

						// Calculate the amount: single unit price * quantity
						$amount = $item['single_unit_price'] * $quantity;

						// Insert order items
						if(SalesReturnItems::create([
							'user_id'            => $loginUserId,
							'order_id'           => $invoiceData->id,
							'product_id'        => $productId,
							'quantity'           => $item['quantity'],
							'return_qty'           => $item['return_qty'],
							'unit_price'         => $item['single_unit_price'],
							'single_unit_price'  => $item['single_unit_price'],
							'tax_rate'           => 2,
							'disc_value'        => $item['discount_rate'] ?? 0,
							'subtotal'           => $amount,
							'disc_type'         => $item['discount_type_id'] ?? 0,
							'return_reason_code'=> $item['return_reason_code'],
							'return_invoice'           => $item['invoice'] ?? 0,
							'invoice_date' => !empty($item['invoice_date']) ? date('d-m-Y', strtotime($item['invoice_date'])) : null,
							'cgst' => $item['cgst'] ?? 0,
							'sgst' => $item['sgst'] ?? 0,
							'igst' => $item['igst'] ?? 0,
							'cess' => $item['cess'] ?? 0
						]))
						{
							$total = $total+$amount;
							// Update current_stock in ProductDetails
							$productDetails = \App\Models\ProductDetails::where('product_id', $productId)->first();
							if ($productDetails) {
								$productDetails->current_stock += $quantity;
								$productDetails->save();
								// Update products.current_stock as sum of all product_details.current_stock
								$totalCurrentStock = \App\Models\ProductDetails::where('product_id', $productId)->sum('current_stock');
								$product = \App\Models\Product::find($productId);
								if ($product) {
									$product->current_stock = $totalCurrentStock;
									$product->save();
								}
							}
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
		catch (\Exception $e) { echo '444'.$e->getMessage();
			DB::rollBack();

			Log::error('Error storing order: ' . $e->getMessage(), [
				'file' => $e->getFile(),
				'line' => $e->getLine(),
				'trace' => $e->getTraceAsString(),
			]);

			return response()->json(['message' => 'Error storing order, please try again.'], 500);
		}

	}
	public function quotationNumber(Request $request)
	{

		$bill = DB::select("select recent_bill_number from settings where setting_type='quotation_number'");
		$discountItems = DiscountModel::where("status",'1')->get();
		$returnTypes = ReturnReasonModel::where("status",'1')->get();
		if(isset($request->party_id))
		$customerDetails 	= LedgerModel::select('opening_balance')->where('id',$request->party_id)->first();
		//print_r($bill);
		//echo str_pad(($bill[0]->recent_bill_number+1),8,"0",STR_PAD_LEFT); ;
		return response()->json([
			'message' => 'Data retrived successfully',
			'data'=>["ref"=> str_pad(($bill[0]->recent_bill_number+1),8,"0",STR_PAD_LEFT),
			"discountItems"=>$discountItems,
			"returnTypes"=>$returnTypes,
			"balance"=>$request->party_id?$customerDetails->opening_balance:""]
		], 200);
	}
	public function partyDetails(Request $request)
	{
		$partyDetails	=	LedgerModel::where('id',$request->partyId)->first();

		return response()->json([
			'message' => 'Data retrived successfully',
			'data'=>$partyDetails
		], 200);
	}
}
