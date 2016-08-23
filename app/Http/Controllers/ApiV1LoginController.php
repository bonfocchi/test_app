<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
#use Illuminate\Support\Facades\Auth;

use App\Http\Requests;

use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Exception;


class ApiV1LoginController extends ApiV1Controller
{


  public function authenticate(Request $request)
 {

     // grab credentials from the request
     $credentials = $request->only('email', 'password');

     try {
          \Config::set('auth.providers.users.model', \App\Admin::class);
         // attempt to verify the credentials and create a token for the user
         if (! $token = JWTAuth::attempt($credentials)) {

             // some credential is missing
             if ( !$request->has('email') || !$request->has('password')){
                $errors = [ "message" => "The request parameters are incorrect, please make sure to follow the HiCat document.",
                            "code" => 400002,
                            "validation" => []
                          ];

                if ( !$request->has('email') ){
                     $errors["validation"]['email'] = [
                         [
                             "key" => "required",
                             "message" => "The email field is required."
                         ]
                     ];
                }

                if ( !$request->has('password') ){
                     $errors["validation"]['password'] = [
                         [
                             "key" => "required",
                             "message" => "The password field is required."
                         ]
                     ];
                }
              }else{
                // failed Authentication
                $errors = [
                            "message" => "The user did not subscribe the application.",
                            "code" => 400003,
                          ];

              }

             return $this->build_reply($request, 0, 400, [], $errors );

         }
     } catch (JWTException $e) {
         // something went wrong whilst attempting to encode the token
         return $this->build_reply($request, 0, 500, [], ['error' => 'Application Error: could_not_create_token'] );
     }

    return $this->build_reply($request, 1, 201, ["token" => $token] );
 }


 public function retrive_login(Request $request)
 {
   $valid = $this->token_valid();

   if($valid['status']){
     $user = $valid['user'];
     $data = array();
     $data['id'] = $user->id;
     $data['hubsynch_id'] = '?';
     $data['email'] = $user->email;
     $data['created_at'] = $user->created_at->toDateTimeString();
     $data['updated_at'] = $user->updated_at->toDateTimeString();

     return $this->build_reply($request, 1, 200, $data );

   }else{
     $errors = [ "message => The request requires user authentication.",
                //"feedback" => token_valid['error'],
                "code" => "401001"
              ];
     return $this->build_reply($request, 0, 401, [], $errors );
   }

 }

 public function regenerate(Request $request){
   $valid = $this->token_valid();

   if($valid['status']){

     try{
         $token = JWTAuth::parseToken()->refresh();
     }catch(TokenInvalidException $e){
       $errors = [ "message => The request requires user authentication.",
                  //"feedback" => 'Token is Invalid (not found), very unlikely to happen here',
                  "code" => "401001"
                ];
       return $this->build_reply($request, 0, 401, [], $errors );
     }

     return $this->build_reply($request, 1, 200, ["token" => $token] );


   }else{
     $errors = [ "message => The request requires user authentication.",
                //"feedback" => token_valid['error'],
                "code" => "401001"
              ];
     return $this->build_reply($request, 0, 401, [], $errors );
   }

 }

 public function logout(Request $request){
   $valid = $this->token_valid();

   if($valid['status']){

     try{
         $token_obj = JWTAuth::parseToken();
         $token = $token_obj->getToken();
         $token_obj->invalidate();
     }catch(TokenInvalidException $e){
       $errors = [ "message => The request requires user authentication.",
                  //"feedback" => 'Token is Invalid (not found), very unlikely to happen here',
                  "code" => "401001"
                ];
       return $this->build_reply($request, 0, 401, [], $errors );
     }

     return $this->build_reply($request, 1, 200, ["deleted_token" => "".$token] );


   }else{
     $errors = [ "message => The request requires user authentication.",
                //"feedback" => token_valid['error'],
                "code" => "401001"
              ];
     return $this->build_reply($request, 0, 401, [], $errors );
   }

 }


}
