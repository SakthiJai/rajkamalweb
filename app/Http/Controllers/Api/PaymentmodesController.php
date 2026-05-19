<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiBaseController;
use App\Http\Requests\Api\Paymentmodes\IndexRequest;
use App\Http\Requests\Api\Paymentmodes\StoreRequest;
use Illuminate\Http\Request;
use App\Models\PaymentModeModel;

class PaymentmodesController extends ApiBaseController
{
    protected $model = PaymentModeModel::class;
    protected $indexRequest = IndexRequest::class;
    protected $storeRequest = StoreRequest::class;

    protected function scopePaymentModeAccess($query)
    {
        $loggedUserId = $this->getLoggedUserScopeId();

        if (!$loggedUserId) {
            return $query;
        }

        return $query->where(function ($innerQuery) use ($loggedUserId) {
            $innerQuery->where('payment_mode.login_user_id', $loggedUserId)
                ->orWhere(function ($fallbackQuery) use ($loggedUserId) {
                    $fallbackQuery->whereNull('payment_mode.login_user_id')
                        ->whereExists(function ($orderQuery) use ($loggedUserId) {
                            $orderQuery->selectRaw('1')
                                ->from('orders')
                                ->whereColumn('orders.id', 'payment_mode.order_id')
                                ->where('orders.user_id', $loggedUserId);
                        });
                });
        });
    }

    protected function modifyIndex($query)
    {
        return $this->scopePaymentModeAccess($query);
    }

    protected function modifyShow($query)
    {
        return $this->scopePaymentModeAccess($query);
    }

    protected function modifyUpdate($query)
    {
        return $this->scopePaymentModeAccess($query);
    }

    protected function modifyDelete($query)
    {
        return $this->scopePaymentModeAccess($query);
    }
}
