<?php

namespace App\Http\Requests\Api\Company;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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

        $rules = [
            'name'    => 'required',
            'address'    => 'required',
            'country'    => 'required',
            'state'    => 'required',
            'pincode'    => 'required',
            'branch_code'    => 'required',
            'business_type'    => 'required',
            'financial_year_from_date'    => 'required',
            'financial_year_to_date'    => 'required',
            'tax_type'    => 'required',
            'phone'    => 'required|numeric',
            'website'    => 'required',
            'email'    => 'required|email',
            'company_reg_type'    => 'required',
            'gstin'    => 'required',
            'reg_date'    => 'required',
            'pan_number' => 'required',
            'drug_lic_no' => 'required',
            'exp_date' => 'required',
            'juris_dicition' => 'required',
            'working_style' => 'required',
            
            // 'lang_id' => 'required',
        ];

        return $rules;
    }
    public function messages()
    {
        return [
            'name.required' => 'Company Name is required.',
            'address.required' => 'Address is required.',
            'country.required' => 'Country is required.',
            'state.required' => 'State is required.',
            'pincode.required' => 'Pincode is required.',
            'branch_code.required' => 'Branch Code is required.',
            'business_type.required' => 'Business Type is required.',
            'financial_year_from_date.required' => 'Financial year From Date is required.',
            'financial_year_to_date' => 'Financial year From Date is required.',
            'tax_type' => 'Tax Type is required.',
            'phone' => 'Phone Number is required.',
            'website' => 'Website  is required.',
            'email' => 'Email is required.',
            'company_reg_type' => 'Company Reg Type is required.',
            'gstin' => 'Gst Number is required.',
            'reg_date' => 'Registration ate is required.',
            'pan_number' => 'Pan Number is required.',
            'drug_lic_no' => 'Drug licno Number is required.',
            'exp_date' => 'Experience Date is required.',
            'juris_dicition' => 'Juris Dicition is required.',
            'working_style' => 'Work Style is required.',
            
        ];
    }
}
