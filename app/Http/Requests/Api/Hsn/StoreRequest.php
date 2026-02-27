<?php
namespace App\Http\Requests\Api\Hsn;

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
           // 'code'  => 'required', 
            //'longitude' => 'required', 
           
        ];      
    }
    public function messages()
    {
        return [
            //'code.required'  => 'HSN/SAC Code is required.',
            // 'longitude.required' => 'Longitude is required.',
            // 'latitude.numeric'   => 'Latitude must be a number.',
            // 'longitude.numeric'  => 'Longitude must be a number.',
        ];
    }
}
