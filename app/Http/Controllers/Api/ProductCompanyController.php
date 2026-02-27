<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiBaseController;
use App\Http\Requests\Api\ProductCompany\IndexRequest;
use App\Http\Requests\Api\ProductCompany\StoreRequest;
use App\Http\Requests\Api\ProductCompany\UpdateRequest;
use App\Http\Requests\Api\ProductCompany\DeleteRequest;
use App\Models\ProductCompany;
use Examyou\RestAPI\Exceptions\ApiException;
use Illuminate\Http\Request;

class ProductCompanyController extends ApiBaseController
{
	protected $model = ProductCompany::class;

	protected $indexRequest = IndexRequest::class;
	protected $storeRequest = StoreRequest::class;
	protected $updateRequest = UpdateRequest::class;
	protected $deleteRequest = DeleteRequest::class;

	public function storeGeoLocaltion(Request $request)
    {
        $geolocaltion = new ProductCompany();

        $geolocaltion->name = $request->name;
        $geolocaltion->print_remark = $request->print_remark;
		$geolocaltion->status = $request->status;
		$geolocaltion->reorder_perference = $request->reorder_perference;
		$geolocaltion->store_room = $request->store_room;
		$geolocaltion->prohibit = $request->prohibit;
		$geolocaltion->invoice_printing = $request->invoice_printing;
		$geolocaltion->dump_days = $request->dump_days;
		$geolocaltion->reorder_formula = $request->reorder_formula;
		$geolocaltion->minimum_margin = $request->minimum_margin;

        $geolocaltion->save();
    
        return response()->json(['message' => 'Geolocation created successfully', 'geolocation' => $geolocaltion], 201);
    }


	
}
