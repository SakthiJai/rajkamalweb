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

		$invoice_details 	= SalesReturn::where('cr_number',$request->invoice)->first();
		$customerDetails 	= LedgerModel::where('id',$invoice_details->party_id)->get();
		
		$partyDetails 		= LedgerCustomerModel::where('id',$invoice_details->party_customer_id)->get();
		
		$products 			= DB::select("SELECT A.quantity,A.single_unit_price,B.mrp,B.sale_rate,A.discount_rate,A.subtotal,B.hsn_sac,B.name,C.cgst as cgst_amount,C.sgst as sgst_amount FROM `sales_return_items` A left join products B on B.id=A.product_id left join tax_catagories C on C.id=B.tax_category WHERE order_id=".$invoice_details->id);
		
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
	   $document->SetWatermarkText($partyDetails[0]->name, 3);
	   // Write some simple Content
	   $document->WriteHTML(view('salesReturnInvoice',[
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
}
