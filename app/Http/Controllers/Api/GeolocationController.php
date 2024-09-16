<?php

namespace  App\Http\Controllers\Api;

use App\Http\Controllers\ApiBaseController;
use App\Http\Requests\Api\Geolocation\StoreRequest;
use App\Http\Requests\Api\Geolocation\UpdateRequest;
use App\Http\Requests\Api\Geolocation\IndexRequest;
use Illuminate\Http\Request;
use App\Models\Geolocation;

class GeolocationController extends ApiBaseController
{
    protected $model = Geolocation::class;
    protected $storeRequest = StoreRequest::class;
	protected $updateRequest = UpdateRequest::class;
    protected $IndexRequest = IndexRequest::class;
    public function storeGeoLocaltion(Request $request)
    {
        $geolocaltion = new Geolocation();

        $geolocaltion->latitude = $request->latitude;
        $geolocaltion->longitude = $request->longitude;

        $geolocaltion->save();
    
        return response()->json(['message' => 'Geolocation created successfully', 'geolocation' => $geolocaltion], 201);
    }

    
    
}
