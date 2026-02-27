<?php

namespace App\Http\Controllers\Api;

use App\Classes\Notify;
use App\Http\Controllers\ApiBaseController;
use App\Http\Requests\Api\Expense\IndexRequest;
use App\Http\Requests\Api\Expense\StoreRequest;
use App\Http\Requests\Api\Expense\UpdateRequest;
use App\Http\Requests\Api\Expense\DeleteRequest;
use App\Models\Expense;
use Carbon\Carbon;
use Examyou\RestAPI\Exceptions\ApiException;

class ExpenseController extends ApiBaseController
{
    protected $model = Expense::class;

    protected $indexRequest = IndexRequest::class;
    protected $storeRequest = StoreRequest::class;
    protected $updateRequest = UpdateRequest::class;
    protected $deleteRequest = DeleteRequest::class;

    public function expenseStore($query)
    {
        $request = request();
        $warehouse = warehouse();

        $expense->company_id = $request->company_id;
        $expense->warehouse_id = $request->warehouse_id;
        $expense->image = $request->image;
        $expense->created_at = $request->created_at;
        $expense->updated_at = $request->updated_at;
        $expense->tax_type = $request->tax_type;
        $expense->party_type = $request->party_type;
        $expense->expense_category = $request->expense_category;
        $expense->entry_date = $request->entry_date;
        $expense->expense_date = $request->expense_date;
        $expense->original_bill_number = $request->original_bill_number;
        $expense->bill_amount = $request->bill_amount;
        $expense->tax_amount = $request->tax_amount;
        $expense->payment_mode = $request->payment_mode;
        $expense->note = $request->note;

        // If user not have admin role
        // then he can only view reords
        // of warehouse assigned to him
        $query = $query->where('expenses.warehouse_id', $warehouse->id);

        if ($request->has('dates') && $request->dates != '') {
            $dates = explode(',', $request->dates);
            $startDate = $dates[0];
            $endDate = $dates[1];

            $query = $query->whereRaw('expenses.date >= ?', [$startDate])
                ->whereRaw('expenses.date <= ?', [$endDate]);
        }

        return $query;
    }

    public function storing(Expense $expense)
    {
        $request = request();
        $loggedUser = user();
        $warehouse = warehouse();

        if ($loggedUser->hasRole('admin')) {
            $expense->user_id = $request->user_id;
        } else {
            $expense->user_id = $loggedUser->id;
        }
        $expense->warehouse_id = $warehouse->id;

        return $expense;
    }

    public function stored(Expense $expense)
    {
        // Notifying to Warehouse
        Notify::send('expense_create', $expense);
    }

    public function updating(Expense $expense)
    {
        $request = request();
        $loggedUser = user();
        $warehouse = warehouse();

        if ($loggedUser->hasRole('admin')) {
            $expense->user_id = $request->user_id;
        } else {
            $expense->user_id = $loggedUser->id;
        }
        $expense->warehouse_id = $warehouse->id;

        return $expense;
    }

    public function updated(Expense $expense)
    {
        // Notifying to Warehouse
        Notify::send('expense_update', $expense);
    }

    public function destroying(Expense $expense)
    {
        $loggedUser = user();

        if (!$loggedUser->hasRole('admin') && $loggedUser->id != $expense->user_id) {
            throw new ApiException("Can not delete other user expense");
        }

        return $expense;
    }

    public function destroyed(Expense $expense)
    {
        // Notifying to Warehouse
        Notify::send('expense_delete', $expense);
    }
}
