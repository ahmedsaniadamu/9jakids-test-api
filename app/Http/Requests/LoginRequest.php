<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator  ;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(){
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(){
        return [
            'password' => 'required|max:6|min:6|string',
            'email' => 'required|email|max:300|min:10'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response() -> json([
            'errors' => $validator -> errors()
        ])) ;
    }
}
