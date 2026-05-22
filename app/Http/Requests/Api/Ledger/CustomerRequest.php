<?php

namespace App\Http\Requests\Api\Ledger;

use Illuminate\Foundation\Http\FormRequest;

class CustomerRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $customerName = $this->input('cus_name')
            ?? $this->input('party_customer_name')
            ?? $this->input('customer_name')
            ?? $this->input('name');

        $mobileNumber = $this->input('mobile_number')
            ?? $this->input('party_customer_mobile')
            ?? $this->input('customer_mobile')
            ?? $this->input('phone_number')
            ?? $this->input('mobile')
            ?? $this->input('phone');

        $email = $this->input('email')
            ?? $this->input('mail_to');

        $this->merge([
            'cus_name' => is_string($customerName) ? trim($customerName) : $customerName,
            'mobile_number' => is_string($mobileNumber) ? trim($mobileNumber) : $mobileNumber,
            'email' => is_string($email) ? trim($email) : $email,
        ]);
    }

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

        if ($this->filled('email')) {
            $rules['email'] = 'required|email';
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
