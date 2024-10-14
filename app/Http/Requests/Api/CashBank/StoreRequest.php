<?php

namespace App\Http\Requests\Api\CashBank;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'account_name'                => 'required',
            'opening_balance'             => 'required',
            'as_of_date'                  => 'required',
            'bank_account_number'         => 'required',
            're_enter_bank_account_number' => 'required',
            'ifsc_code'                   => 'required',
            'bank_branch_name'            => 'required',
            'account_holder_name'         => 'required',
            'upi_id'                      => 'nullable',

        ];
    }

    public function messages()
    {
        return [
            'account_name.required' => 'Account name is required.',
            'opening_balance.required' => 'Opening balance is required.',
            'opening_balance.numeric' => 'Opening balance must be a number.',
            'as_of_date.required' => 'Date is required.',
            'as_of_date' => 'A valid date is required.',
            'bank_account_number.required' => 'Bank account number is required.',
            're_enter_bank_account_number.required' => 'Please re-enter the bank account number.',
            // 're_enter_bank_account_number.same' => 'The bank account numbers must match.',
            'ifsc_code.required' => 'IFSC code is required.',
            'bank_branch_name.required' => 'Bank branch name is required.',
            'account_holder_name.required' => 'Account holder name is required.',
            'upi_id.string' => 'UPI ID must be a string.',
        ];
    }
}
