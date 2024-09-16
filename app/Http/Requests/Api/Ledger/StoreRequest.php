<?php

namespace App\Http\Requests\Api\Ledger;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
			'party_name'    => 'required|string',
			'station'       => 'required|integer',
            'stock_country' => 'required|integer',
            'stock_state'   => 'required|integer',
            'stock_city'    => 'required|string',
            'account_group' => 'required|string',
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
            'party_name.required' => 'party name is required.',
            'station.required' => 'station is required.',
            'stock_country.required' => 'country is required.',
            'stock_state.required' => 'state is required.',
            'stock_city.required' => 'city is required.',
            'account_group.required' => ' group is required.',
            'mobile_number.required' => 'number is required.',
        ];
    }
}