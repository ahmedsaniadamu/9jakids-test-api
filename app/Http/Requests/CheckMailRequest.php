<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator  ; 
use App\Mail\VerificationMail;
use App\Models\User as Parents;
use Illuminate\Support\Facades\Mail;
use Grosv\LaravelPasswordlessLogin\LoginUrl;

use Mockery\Undefined;

class CheckMailRequest extends FormRequest
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
            'email' => 'required|email|unique:parents,email',
            'phone' => 'required|unique:parents,phone|string|max:20|min:10|regex:/^[0-9]+$/',            
        ];
    }

    protected function failedValidation(Validator $validator){
         /* the  response to send back to client */
          $response = [
            'sucess'=> false,                 
            'errors' => $validator -> errors(),        
          ];
         /*-----------------------------------------------------------------------
            send an mail to only already registered parent if email address 
            exits in the database
         ------------------------------------------------------------------------*/
         $parent_email = $this -> input('email');
         $email_error = $validator -> errors() -> get('email')[0];

         if( isset($email_error) && $email_error == 'The email has already been taken.'){
             /*  send mail to the parent email address  with direct login url */                                          
              $this -> sendMail($parent_email);

              unset($response['errors']);
              $response['message'] = 'The email has already been taken. A login link is send to your email address ('.$parent_email.')';
         }

         throw new HttpResponseException( response() -> json($response) );
    }
    
    private function sendMail($emailAdress){
        /*-----------------------------------------------------------------------------------
           generate a direct login url using Grosv\LaravelPasswordlessLogin\LoginUrl package
        -------------------------------------------------------------------------------------- */
        $parent = Parents::where('email', $emailAdress ) -> first();
        $generator = new LoginUrl($parent);
        $generator -> setRedirectUrl('/');
        $url = $generator ->generate() ;
        Mail::to( $emailAdress) -> send( new VerificationMail($url,$parent->name) );
    }
}
