<?php
namespace App\Http\Requests\Api\Birthday;

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
        return [
            'birthday_date'  => 'required', 
            'anniversary_date' => 'required', 
        ];      
    }
    public function messages()
    {
        return [
            'birthday_date.required'  => 'Birthday date is required.',
            'anniversary_date.required' => 'Anniversary date is required.',
        ];
    }
}
