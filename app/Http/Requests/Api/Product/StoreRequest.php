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

            'name'         =>'required',
            'unit_1st'     =>'required',
            'hsn_sac'      =>'required',
            'tax_category' =>'required',
            'company'      =>'required',
            // 'purchase_rate'=>'required',
            // 'sale_rate'    =>'required',
        ];

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
            // 'purchase_rate.required' => 'Purchase rate is required.',
            // 'sale_rate.required' => 'Sales rate is required.',

        ];
    }
}
