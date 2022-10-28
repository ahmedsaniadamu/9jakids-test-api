<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class RegisterationRequest extends FormRequest
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
           'name' => 'required|string|min:5|max:150',
           'email' => 'required|email|unique:parents,email|min:10|max:300',
           'phone' => 'required|unique:parents,phone|string|max:20|min:10|regex:/^[0-9]+$/',             
           'children' => 'required|array',
           'children.*.name' => 'required|max:150|string',
           'children.*.age_range' => 'required|max:6|string',
           'children.*.gender' => 'required|max:7|string'
        ];
    }

    protected function failedValidation(Validator $validator){
        throw new HttpResponseException( 
            response() -> json([
               'sucess'=> false,
               'status' => 400 ,
               'errors' => $validator -> errors()
               ])
        );
   }   
}
