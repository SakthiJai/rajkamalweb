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
			'name'    => 'required|string',
			
            'ledger_id' => 'required|integer',
            
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
            'name.required' => 'Customer name is required.',
            'ledger_id.required' => 'Legder is required.',
            
            'mobile_number.required' => 'number is required.',
        ];
    }
}