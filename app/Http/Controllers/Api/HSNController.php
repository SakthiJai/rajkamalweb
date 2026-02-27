<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiBaseController;
use App\Http\Requests\Api\HSN\IndexRequest;
use App\Http\Requests\Api\HSN\StoreRequest;
use App\Http\Requests\Api\HSN\UpdateRequest;
use App\Http\Requests\Api\HSN\DeleteRequest;
use App\Models\HSN;
use Illuminate\Http\Request;
use Examyou\RestAPI\Exceptions\ApiException;

class HSNController extends ApiBaseController
{
	protected $model = HSN::class;

	protected $indexRequest = IndexRequest::class;
	protected $storeRequest = StoreRequest::class;
	protected $updateRequest = UpdateRequest::class;
	protected $deleteRequest = DeleteRequest::class;


	public function storeGeoLocaltion(Request $request)
    {
        $hsn = new HSN();

        $hsn->code = $request->code;
        $hsn->name = $request->name;
        $hsn->gst = $request->gst;
        $hsn->cgst = $request->cgst;
        $hsn->sgst = $request->sgst;
        $hsn->lgst = $request->lgst;

        $hsn->save();
    
        return response()->json(['message' => 'HSN created successfully', 'hsn' => $hsn], 201);
    }
}
