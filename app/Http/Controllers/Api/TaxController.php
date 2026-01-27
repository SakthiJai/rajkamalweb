<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiBaseController;
use App\Http\Requests\Api\Tax\IndexRequest;
use App\Http\Requests\Api\Tax\StoreRequest;
use App\Http\Requests\Api\Tax\UpdateRequest;
use App\Http\Requests\Api\Tax\DeleteRequest;
use App\Models\Tax;

class TaxController extends ApiBaseController
{
	protected $model = Tax::class;

	protected $indexRequest = IndexRequest::class;
	protected $storeRequest = StoreRequest::class;
	protected $updateRequest = UpdateRequest::class;
	protected $deleteRequest = DeleteRequest::class;

<<<<<<< Updated upstream
    public function modifyIndex($query)
    {
        $request = request();

        $query = $query->whereNull('taxes.parent_id');

        return $query;
    }

    public function stored(Tax $tax)
    {
        $this->insertOrUpdateTaxData($tax);

        return $tax;
    }

    public function updated($tax)
    {
        $this->insertOrUpdateTaxData($tax);

        return $tax;
    }

    public function insertOrUpdateTaxData($tax)
    {
        $request = request();

        $multipleTaxes = $request->multiple_taxes;
        foreach ($multipleTaxes as $multipleTax) {
            if ($multipleTax['id'] == '') {
                $childTax = new Tax();
            } else {
                $id = $this->getIdFromHash($multipleTax['id']);
                $childTax = Tax::find($id);
            }
            $childTax->name = $multipleTax['tax_name'];
            $childTax->rate = $multipleTax['rate'];
            $childTax->parent_id = $tax->id;
            $childTax->save();

        }
    }
}


=======
//     public function modifyIndex($query)
//    {
//          return $query;
//     }

//     public function stored(Request $request)
//     {
//         $taxCategory = new TaxCategory();
//         $taxCategory->sales_type = $request->sales_type;        $taxCategory->local = $request->local;
//          $taxCategory->central = $request->central;
//          $taxCategory->lgst = $request->lgst;
//         $taxCategory->cgst = $request->cgst;
//          $taxCategory->cess = $request->cess;
//          $taxCategory->nature_of_transaction = $request->nature_of_transaction;
//         $taxCategory->tax_ability = $request->tax_ability;
//         $taxCategory->lgst_ledger = $request->lgst_ledger;
//         $taxCategory->cgst_ledger = $request->cgst_ledger;
//          $taxCategory->sgst_ledger = $request->sgst_ledger;
//         $taxCategory->cess_ledger = $request->cess_ledger;
//         $taxCategory->save();

//          return response()->json(['message' => 'Tax Category created successfully', 'tax_category' => $taxCategory]);
//      }

//      public function updated(Request $request, $id)
//      {
//          $taxCategory = TaxCategory::find($id);

//          if (!$taxCategory) {
//             return response()->json(['error' => 'Tax Category not found'], 404);
//         }
//         $taxCategory->sales_type = $request->sales_type;
//          $taxCategory->local = $request->local;
//         $taxCategory->central = $request->central;
//         $taxCategory->igst = $request->igst;
//          $taxCategory->cgst = $request->cgst;
//         $taxCategory->sgst = $request->sgst;
//          $taxCategory->cess = $request->cess;
//          $taxCategory->nature_of_transaction = $request->nature_of_transaction;
//          $taxCategory->tax_ability = $request->tax_ability;
//          $taxCategory->igst_ledger = $request->igst_ledger;
//          $taxCategory->cgst_ledger = $request->cgst_ledger;
//         $taxCategory->sgst_ledger = $request->sgst_ledger;
//          $taxCategory->cess_ledger = $request->cess_ledger;
//          $taxCategory->save();

//          return response()->json(['message' => 'Tax Category updated successfully', 'tax_category' => $taxCategory]);
//     }

//      public function taxshow(Request $request, $id)
//      {
//          $taxCategory = TaxCategory::find($id);

//          if (!$taxCategory) {
//              return response()->json(['error' => 'Tax Category not found'], 404);
//         }

//          return response()->json(['tax_category' => $taxCategory]);
//      }
// // //for list
//      public function delete(Request $request, $id)
//      {
//          $taxCategory = TaxCategory::find($id);

//          if (!$taxCategory) {
//              return response()->json(['error' => 'Tax Category not found'], 404);
//          }

//          $taxCategory->delete();

//          return response()->json(['message' => 'Tax Category deleted successfully']);
//      }
 }
>>>>>>> Stashed changes
