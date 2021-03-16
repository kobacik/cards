<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CardRequest extends FormRequest
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
            'title' => 'required|max:255',
            'name' => 'max:50',
            'phone' => 'required|max:20',
            'email' => 'max:50|email:rfc,dns',
            'address' => 'max:255',
            'ip_address' => 'ip'
        ];
    }
}
