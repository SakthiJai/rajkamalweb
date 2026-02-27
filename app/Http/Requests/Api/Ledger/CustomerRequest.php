<?php

namespace App\Http\Requests\Api\Ledger;

use Illuminate\Foundation\Http\FormRequest;

class CustomerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
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
        $rules = [
			'cus_name'    => 'required|string',
            'mobile_number' => 'required',
		];

        if ($this->filled('mail_to')) {
            $rules['mail_to'] = 'required|email';
        }

		return $rules;
    }

    public function messages()
    {
        return [
            'cus_name.required' => 'Customer name is required.',
            'mobile_number.required' => 'number is required.',
        ];
    }
}