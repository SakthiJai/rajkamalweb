<?php

namespace  App\Http\Controllers\Api;
use App\Http\Controllers\ApiBaseController;
use App\Http\Requests\Api\Birthday\StoreRequest;
use App\Http\Requests\Api\Birthday\UpdateRequest;
use App\Http\Requests\Api\Birthday\IndexRequest;
use App\Models\Birthday;
use Illuminate\Http\Request;

class BirthdayController extends ApiBaseController
{
    protected $model = Birthday::class;
    protected $storeRequest = StoreRequest::class;
	protected $updateRequest = UpdateRequest::class;
    protected $IndexRequest = IndexRequest::class;

    // public function storeBirthday(Request $request)
    // {
    //     $birthday = new Birthday();

    //     $birthday->birthday_date = $request->birthday_date;
    //     $birthday->anniversary_date = $request->anniversary_date;

    //     $birthday->save();
    
    //     return response()->json(['message' => 'Birthday created successfully', 'geolocation' => $birthday], 201);
    // }
}
