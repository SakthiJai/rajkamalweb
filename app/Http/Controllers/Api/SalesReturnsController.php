<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\ApiBaseController;
use App\Http\Requests\Api\SalesReturn\IndexRequest;
use App\Http\Requests\Api\SalesReturn\StoreRequest;
use App\Http\Requests\Api\SalesReturn\UpdateRequest;
use App\Http\Requests\Api\SalesReturn\DeleteRequest;
use App\Models\SalesReturn;
use App\Models\LedgerModel;
use App\Models\LedgerCustomerModel;
use \Mpdf\Mpdf as PDF; 
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class SalesReturnsController extends ApiBaseController
{

	protected $model = SalesReturn::class;

	protected $indexRequest = IndexRequest::class;
	protected $storeRequest = StoreRequest::class;
	protected $updateRequest = UpdateRequest::class;
	protected $deleteRequest = DeleteRequest::class;

	public function __construct()
	{
		parent::__construct();

		$this->orderType = "sales-returns";
	}
	public function getInvoicePdf(Request $request)
	{
		$documentFileName = "sales_return_".$request->invoice.".pdf";

		$invoice_details  = SalesReturn::where('cr_number',$request->invoice)->first();
		if (!$invoice_details) {
			// Return error response or handle gracefully
			return response()->json(['error' => 'Sales Return not found'], 404);
		}
		$customerDetails  = LedgerModel::where('id',$invoice_details->party_id)->get();
		$partyDetails     = LedgerCustomerModel::where('id',$invoice_details->party_customer_id)->get();
		$company = \App\Models\Company::first(); 
		// Use items from payload if present, otherwise fallback to DB
		$products = [];
		if ($request->has('items') && is_array($request->items) && count($request->items) > 0) {
			foreach ($request->items as $item) {
				$products[] = (object) $item;
			}
		} else {
			$products = DB::select("
				SELECT 
				A.quantity,
				A.single_unit_price,
				A.discount_rate,
				A.subtotal,
				A.return_qty,
				B.mrp,
				B.hsn_sac,
				B.name,
				H.cgst as cgst,
				H.sgst as sgst,
				H.lgst as igst,
				H.cess as cess
				FROM sales_return_items A
				LEFT JOIN products B ON B.id = A.product_id
				LEFT JOIN hsc_sac H ON H.id = B.hsn_sac
				WHERE A.order_id = ".$invoice_details->id
			);
		}
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
	   if (isset($partyDetails[0])) {
		   $document->SetWatermarkText($partyDetails[0]->name, 3);
	   }
	   // Write some simple Content
	   $document->WriteHTML(view('salesReturnInvoice', [
		   'invoice_details' => $invoice_details,
		   'customer' => $customerDetails,
		   'party' => $partyDetails,
		   'products' => $products,
		   'company' => [$company], // Pass as array for Blade compatibility
	   ]));
	  // $document->WriteHTML('<p>Write something, just for fun!');

	   // Save PDF on your public storage 
	   Storage::disk('file')->put($documentFileName, $document->Output($documentFileName, "S"));

	   // Get file back from storage with the give header informations
		Storage::disk('file')->download($documentFileName, 'Request', $header);

		$invoice_details->invoice_path = config('app.url') .'/'.$documentFileName;
		$invoice_details->save();
		echo config('app.url') .'/'.$documentFileName;

		// Fetch company info from DB

	}
	/**
	 * Store a newly created sales return in storage and update product stock.
	 */
	public function store()
	{
		// Call parent logic or your existing logic to save the sales return first
		$salesReturn = SalesReturn::create(request()->all());

		// If items are present in the request, update stock for each
		$items = request()->input('items', []);
		if (is_array($items)) {
			foreach ($items as $item) {
				$productId = isset($item['product_id']) ? $item['product_id'] : (isset($item['item_id']) ? $item['item_id'] : null);
				$returnQty = isset($item['return_qty']) ? $item['return_qty'] : 0;
				if ($productId && $returnQty > 0) {
					// Update ProductDetails stock
					$productDetailsList = \App\Models\ProductDetails::where('product_id', $productId)->get();
					foreach ($productDetailsList as $productDetails) {
						$productDetails->current_stock += $returnQty;
						$productDetails->save();
					}
					// Update main products table stock as sum of all product_details
					$totalCurrentStock = \App\Models\ProductDetails::where('product_id', $productId)->sum('current_stock');
					$product = \App\Models\Product::find($productId);
					if ($product) {
						$product->current_stock = $totalCurrentStock;
						$product->save();
					}
				}
			}
		}
		return response()->json(['success' => true, 'sales_return' => $salesReturn]);
	}
}
