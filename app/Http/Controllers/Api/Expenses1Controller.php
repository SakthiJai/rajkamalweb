<?php

namespace App\Http\Controllers\Api;

use App\Classes\Notify;
use App\Http\Controllers\ApiBaseController;
use App\Http\Requests\Api\Expenses1\IndexRequest;
use App\Http\Requests\Api\Expenses1\StoreRequest;
use App\Http\Requests\Api\Expenses1\UpdateRequest;
use App\Http\Requests\Api\Expenses1\DeleteRequest;
use App\Models\Expenses1;
use App\Models\PaymentMode;
use App\Models\ExpenseCategory;
use App\Models\LedgerModel;
use App\Models\StaffMember;
use Illuminate\Http\Request;
use Examyou\RestAPI\Exceptions\ApiException;

class Expenses1Controller extends ApiBaseController
{
    protected $model = Expenses1::class;

    protected $indexRequest = IndexRequest::class;
    protected $storeRequest = StoreRequest::class;
    protected $updateRequest = UpdateRequest::class;
    protected $deleteRequest = DeleteRequest::class;

   /*  public function modifyIndex($query)
     {
        $loggedUser = user();
        $request = request();
        $query = $query->join('orders', 'orders.id',  '=', 'order_payments.order_id');
         return $query;
    }*/

    public function saveExpenses(Request $request)
    {
        try {
			if($request->selectedExpense=="null")
			{
                $exp = Expenses1::where('orginal_bill_number',  $request->input('orginal_bill_number'))->first();

                if ($exp) {
                    return response()->json(['message' => 'Existing Data.', 202]);
                }
                else {
                    if(!$this->createExpense($request))
                    {
                        return response()->json(['message' => 'Expense Created successfully.', 201]);
                    }
                }
			}
			else{
                $exp = Expenses1::where('orginal_bill_number',  $request->input('orginal_bill_number'))
                         ->where('id', '!=', $request->selectedExpense)
                        ->first();

                if ($exp) {
                    return response()->json(['message' => 'Existing Original Bill Number.', 202]);
                }
                else {
                    if(!$this->updateexpense($request))
                    {
                        return response()->json(['message' => 'Expense updated successfully.', 201]);
                    }
                }
			}
		}
		catch(\Illuminate\Database\QueryException $ex){
            //dd($ex->getMessage());
             return response()->json(['message' => $ex->getMessage()], 500);
            // Note any method of class PDOException can be called on $ex.
        }

    }
    public function createExpense($request)
	{
		try{
		    $saved = false;
            $exp = new Expenses1();
            $exp->tax_type = $request->input('tax_type');
            $exp->party_id = $request->input('party_id');
            $exp->expense_category_id = $request->input('expense_category_id');
            $exp->payment_mode_id = $request->input('payment_mode_id');
            $exp->orginal_bill_number = $request->input('orginal_bill_number');
            $exp->bill_amount = $request->input('bill_amount');
            $exp->tax_amount = $request->input('tax_amount');
            $exp->order_date = $request->input('order_date');
            $exp->expense_date = $request->input('expense_date');
            $exp->image = $request->input('image');
            $exp->notes = $request->input('notes');
            $exp->user_id =  auth('api')->user()->id;
            $exp->is_delete = 0;
            if($exp->save())
            {
                $saved= true;
            }

			return $saved;
		}
		catch(\Illuminate\Database\QueryException $ex){
			//dd($ex->getMessage());
			echo  response()->json(['message' => $ex->getMessage()], 500);
			// Note any method of class PDOException can be called on $ex.
		  }

	}

    public function updateexpense($request)
	{

		try{
		    $updated = false;
            $exp = Expenses1::find($request->selectedExpense);
            if (!$exp) {
            return response()->json([
                'message' => 'Expense not found'
            ], 404);
        }
            $exp->tax_type = $request->input('tax_type');
            $exp->party_id = $request->input('party_id');
            $exp->expense_category_id = $request->input('expense_category_id');
            $exp->payment_mode_id = $request->input('payment_mode_id');
            $exp->orginal_bill_number = $request->input('orginal_bill_number');
            $exp->bill_amount = $request->input('bill_amount');
            $exp->tax_amount = $request->input('tax_amount');
            $exp->order_date = $request->input('order_date');
            $exp->expense_date = $request->input('expense_date');
            $exp->image = $request->input('image');
            $exp->notes = $request->input('notes');
            $exp->modified_user_id =  auth('api')->user()->id;
			if($exp->save())
			{
                $updated  = true;
			}

			return $updated;
		}
		catch(\Illuminate\Database\QueryException $ex){
			echo  response()->json(['message' => $ex->getMessage()], 500);
		}

	}

    public function deleteExpenses($id)
    {
       // Log::error("No expense found with ID: {$id}");

        $exp = Expenses1::find($id);
         $exp->delete();

        return response()->json(['message' => 'Data updated successfully', 201]);




    }


    // public function deleteExpenses($id)
    // {
    //     $expense = Expenses1::find($id);

    //     if (!$expense) {
    //         return response()->json(['error' => 'Expense not found in this id '], 404);
    //     }

    //     $expense->is_delete = 1; // Mark as deleted
    //     $expense->save();

    //     return response()->json(['success' => 'Expense deleted successfully...', 200]);
    // }

    public function getExpensesDetails($id)
	{
		try{

            $exp = Expenses1::where('id', $id)->first();
            $paymentData 	=	PaymentMode::where("id",$exp->payment_mode_id)->first();
            $partyData	=	LedgerModel::where('id',$exp->party_id)->first();
            $categoryData	=	ExpenseCategory::where('id',$exp->expense_category_id)->first();

            return response()->json([
                'message' => 'Data retrived successfully',
                'data'=>["expenseData"=>$exp,"paymentData"=>$paymentData,"partyData"=>$partyData,"categoryData"=>$categoryData]
            ], 200);
		}
		catch(\Exception $ex)
		{
			return response()->json(['message' => $ex->getMessage()], 500);
		}
		catch(\Illuminate\Database\QueryException $ex){
			//dd($ex->getMessage());
			return response()->json(['message' => $ex->getMessage()], 500);
			// Note any method of class PDOException can be called on $ex.
		}
	}


    function getInvoicePdf(Request $request)
	{
		 $documentFileName = "Expenses1".$request->invoice.".pdf";
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
        $document->WriteHTML(view('expenseInvoice',[
			'invoice_details' => $invoice_details,
			'customer'=>$customerDetails,
			'party'=>$partyDetails,
			"products"=> $products
		]));
         Storage::disk('file')->put($documentFileName, $document->Output($documentFileName, "S"));
          Storage::disk('file')->download($documentFileName, 'Request', $header);
		 $invoice_details->invoice_path = config('app.url') .'/'.$documentFileName;
		 $invoice_details->save();
		 echo config('app.url') .'/'.$documentFileName;
	}


}
