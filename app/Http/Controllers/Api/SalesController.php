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
use App\Models\OrderPayment;
use Illuminate\Support\Str;
use App\Traits\OrderTraits;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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

			$order->save();

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
							'product_id '        => $item['item_id'],
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

			OrderItem::create([
				'company_id' => "1",
				'payment_id' => "1",
				'order_id '  => $order->id ?? 0,
				'amount'     => $request->subtotal ?? 0.00
			]);

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
}
