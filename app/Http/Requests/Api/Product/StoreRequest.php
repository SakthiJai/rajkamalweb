<?php

namespace App\Http\Requests\Api\Product;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */

    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $company = company();
        $loggedUser = auth('api')->user();

        $rules = [

            'name' => 'required',
            'unit_1st' =>'required',
            'hsn_sac'=>'required',

            'tax_category'=>'required',
          'company'=>'required',

//             'slug'    => [
//                 'required',
//                 Rule::unique('products', 'slug')->where(function ($query) use ($company) {
//                     return $query->where('company_id', $company->id);
//                 })
//             ],
//             'barcode_symbology'    => 'required',
//             'item_code'    => [
//                 'required',
//                 Rule::unique('products', 'item_code')->where(function ($query) use ($company) {
//                     return $query->where('company_id', $company->id);
//                 })
//             ],
//             'category_id'
// => 'required',
// 'unit_id'
//   => 'required',
        ];

        // if($this->product_type == 'single') {
        //     $rules['purchase_price'] = 'required|gt:0';
        //     $rules['sales_price'] = 'required||gt:0|gte:purchase_price';
        // }

        // // If purchase or sales includes tax
        // if ($this->purchase_tax_type == 'inclusive' || $this->sales_tax_type == 'inclusive') {
        //     $rules['tax_id'] = 'required';
        // }

        // if ($loggedUser->hasRole('admin')) {
        //     $rules['warehouse_id'] = 'required';
        // }

        return $rules;
    }
    public function messages()
    {
        return [
            'name.required' => 'Product name is required.',
            'unit_1st.required' => 'Unit 1st  is required.',
            'hsn_sac.required' => 'HSN/SAC is required.',
            'tax_category.required' => 'Tax Category is required.',
            'company.required' => 'Company is required.',


        ];
    }
}
