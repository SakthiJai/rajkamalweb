<?php

namespace App\Http\Controllers\Api;

use App\Classes\Common;
use App\Http\Controllers\ApiBaseController;
use App\Http\Requests\Api\Quotations\IndexRequest;
use App\Http\Requests\Api\Quotations\StoreRequest;
use App\Http\Requests\Api\Quotations\UpdateRequest;
use App\Http\Requests\Api\Quotations\DeleteRequest;
use App\Models\Order;
use App\Models\LedgerModel;
use App\Models\LedgerCustomerModel;

use \Mpdf\Mpdf as PDF;
use App\Traits\OrderTraits;
use Examyou\RestAPI\ApiResponse;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
 use Illuminate\Support\Facades\Storage;

class QuotationController extends ApiBaseController
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

        $this->orderType = "quotations";
    }

    public function index()
    {
        // This endpoint is already quotation-specific via OrderTraits,
        // so we ignore the shared salestype query param to avoid package-level misfiltering.
        request()->query->remove('salestype');
        request()->request->remove('salestype');

        return parent::index();
    }

    public function convertToSale(Request $request, $id)
    {
        $order = Order::where('unique_id', $id)->first();

        if ($order->order_type == "quotations") {
            $order->order_type = 'sales';
            $order->order_status = 'confirmed';
            $order->save();

            $orderItems = $order->items;

            foreach ($orderItems as $orderItem) {
                $productId = $orderItem->product_id;
                $warehouseId = $order->warehouse_id;

                // Update warehouse stock for product
                Common::recalculateOrderStock($warehouseId, $productId);
            }

            Common::storeAndUpdateOrder($order, "");

            Common::updateUserAmount($order->user_id, $order->warehouse_id);

            // Updating Warehouse History
            Common::updateWarehouseHistory('order', $order, "add_edit");

            return ApiResponse::make('Success', []);
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
			'data'=>["ref"=> str_pad(($bill[0]->recent_bill_number),8,"0",STR_PAD_LEFT),
			"discountItems"=>$discountItems,
			"returnTypes"=>$returnTypes,
			"balance"=>$request->party_id?$customerDetails->opening_balance:""]
		], 200);
	}

    function getInvoicePdf(Request $request)
	{
		 $documentFileName = "Quotations".$request->invoice.".pdf";
		 $invoice_details 	= Order::where('invoice_number',$request->invoice)->first();
		 $partyDetails 		= LedgerCustomerModel::where('id',operator: $invoice_details->party_customer_id)->get();
		 $customerDetails 	= LedgerModel::where('id',$partyDetails[0]->ledger_id)->get();
		 $products 			= DB::select("SELECT A.quantity,B.mrp,B.sale_rate,A.discount_rate,A.subtotal,B.hsn_sac,B.name,C.cgst as cgst_amount,C.sgst as sgst_amount FROM `order_items` A left join products B on B.id=A.product_id left join tax_catagories C on C.id=B.tax_category WHERE order_id=".$invoice_details->id);

         //print_r($invoice_details);exit;
         $document = new PDF( [
            'mode' => 'utf-8',
            'format' => [220, 196],
            'margin_header' => '1',
            'margin_top' => '10',
            'margin_bottom' => '20',
            'margin_footer' => '2',
        ]);
         $header = [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$documentFileName.'"'
        ];
		$document->watermark("your watermark text", 20, 10, 0.2);
        // Write some simple Content
        $document->WriteHTML(view('quotationInvoice',[
			'invoice_details' => $invoice_details,
			'customer'=>$customerDetails,
			'party'=>$partyDetails,
			"products"=> $products
		]));
        // Save PDF on your public storage
        Storage::disk('file')->put($documentFileName, $document->Output($documentFileName, "S"));
        // Get file back from storage with the give header informations
         Storage::disk('file')->download($documentFileName, 'Request', $header);
		 $invoice_details->invoice_path = config('app.url') .'/'.$documentFileName;
		 $invoice_details->save();
		 echo config('app.url') .'/'.$documentFileName;
	}


}
