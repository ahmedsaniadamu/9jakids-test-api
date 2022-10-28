<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckMailRequest;
use App\Http\Requests\ChildrenLoginRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterationRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\LoginMail; 
use App\Models\User as Parents;
use App\Models\Children;
use Illuminate\Support\Str;
 
class ApiController extends Controller {

    //check if email or phone number already exist
    public function checkMail(CheckMailRequest $request){
        //validate incoming request using form request
         $request -> validated();
         //returns a success message if the request is valid
         return response() -> json([
             'success' => true ,
             'message' => 'email address and phone number are available fo use'
         ]);
    }

    //register a parent with childrens
    public function register(RegisterationRequest $request){

            //validate incoming request using form request
            $request -> validated();            
                         
            /*
               generate a random 6 digit password for a parent which will be send
               to parent email address. then the hashed value will be stored in the
               database for authentication.
            */
            $password = Str::random(6) ;

            //store parent data to the database
            $parent = Parents::create([
               'name'  => $request -> name ,
               'email' => $request -> email ,
               'phone' => $request -> phone,
               'password' => Hash::make($password),
            ]); 
            //create personal access token for the new registered parent
            $parent_token = $parent -> createToken($parent -> email . '_token') -> plainTextToken;

            // get all the childrens data and  assign a unique 6 digit code for every child 
            $childrens = [] ;

            for($i = 0 ; $i < count( $request -> children ) ; $i++){
                $childrens[] = [
                    'name' => $request -> input('children.' . $i . '.name') ,
                    'age_range' => $request -> input('children.' . $i . '.age_range') ,
                    'gender' => $request -> input('children.' . $i . '.gender') ,
                    'parent_id' => $request -> input('children.' . $i . '.parent_id', $parent -> id ) ,
                    'code' => $request -> input( 
                                                  'children.' . $i . '.code',
                                                   $this -> generateChildCode( mt_rand(100000 , 999999) ) 
                                                ) 
                    ] ;
             }   
                             
            //store childrens data to the database 
            $parent -> children() -> createMany( $childrens ) ;
            /*----------------------------------------------------------------------------
               send login credentials to parent email address (random 6 digit password generated)
             -----------------------------------------------------------------------------*/
             Mail::to($parent -> email) 
                   -> send(new LoginMail( $password, $parent -> name ));
            
             /* Finally return a json response */
             return response() -> json([                
                 'sucess' =>   true,
                 'status' => 201 ,
                 'message' => 'Registration is done succesfully!',
                 'email' => $parent -> email ,
                 'token' => $parent_token
            ]);        
    }          



    public function login(LoginRequest $request){
        
        //validate incoming login request
        $request -> validated();
        //check if user exist        
        $parent = Parents::where('email', $request -> email) -> first();

        if( !$parent || !Hash::check($request -> password , $parent -> password) ){
                  return response() -> json([
                    'status' => 401 ,
                    'message' => 'Invalid Credentials please check and try again'
                   ]);
         }
        else{
              //generate a login token                     
               $token = $parent -> createToken( $parent -> email . '_token') -> plainTextToken ;
                    
                return response() -> json([
                    'status' => 200 ,
                    'message' => 'logged in successfully!',
                    'token' => $token,
                    'id' => $parent -> id,
                    'name' => $parent -> name ,
                    'email' => $parent -> email , 
                    'childrens' => $parent -> children
                ]);              
        }
    }
    
    // children login function
    public function childrenLogin(ChildrenLoginRequest $request){
        
        $children = Children::where('code',$request -> code ) -> first();

        if(!$children){
            return response() -> json([
                'status' => 401 ,
                'message' => 'Invalid Code please check and try again'
              ]);
         }
         else{
            return response() -> json([
                 'status' => 200 ,
                 'message' => 'You are logged in successfully!',
                 'name' => $children -> name ,
                 'age_range' => $children -> age_range,
                 'gender' => $children -> gender
               ]);
         }
    }

    //-------------------------------------------------------------------------------------//
    public function logout(){

         auth() -> user() -> tokens() -> delete() ;
         
         return response() -> json([
            'status' => 200 ,
            'message' => 'loggged out successfully!'
         ]);
    }

   /* -------------------------------------------------------------------------
     verify and check if child code already exist in the database.
     if so then re-run the function to generate and return a child new code
    ---------------------------------------------------------------------------*/
   private function generateChildCode($code){

       $validate = Validator::make( 
                                    [ 'code'=> $code ], 
                                    ['code' => 'unique:childrens,code']
                                  )
                                  -> passes() ;
       return $validate ? $code : $this -> generateChildCode($code) ;
   }
}