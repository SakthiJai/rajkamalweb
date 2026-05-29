<?php

namespace App\Http\Controllers\Api\Common;

use App\Http\Controllers\ApiBaseController;
use App\Models\PaymentDueDate;
use Examyou\RestAPI\ApiResponse;
use Illuminate\Http\Request;

class PaymentDueDateController extends ApiBaseController
{
    public function index()
    {
        $paymentDueDate = PaymentDueDate::query()->first();

        return ApiResponse::make('Success', [
            'paymentDueDate' => [
                'id' => $paymentDueDate ? $paymentDueDate->xid : null,
                'day' => $paymentDueDate ? $paymentDueDate->day : 30,
            ],
        ]);
    }

    public function update(...$args)
    {
        $request = $args[0] ?? app(Request::class);

        validator($request->all(), [
            'day' => 'required|integer|min:0|max:365',
        ])->validate();

        $paymentDueDate = PaymentDueDate::query()->first();

        if (!$paymentDueDate) {
            $paymentDueDate = new PaymentDueDate();
        }

        $paymentDueDate->day = $request->day;
        $paymentDueDate->save();

        return ApiResponse::make('Success', [
            'paymentDueDate' => [
                'id' => $paymentDueDate->xid,
                'day' => $paymentDueDate->day,
            ],
        ]);
    }
}
