<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
#use Illuminate\Support\Facades\Auth;

use App\Http\Requests;

use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Exception;

class ApiV1Controller extends Controller
{

  /**
   * Build and reply json
   *
   * @param  obj  $request
   * @param  int  $success
   * @param  int  $code
   * @param  array  $data
   * @param  array  $errors
   * @return json
   */
   public function build_reply($request, $success, $code, $data = [], $errors = [] ){
     $reply =  [ "success" => $success,
                 "code" => $code,
                 "meta" => [
                     "method" => $request->method(),
                     "endpoint" => $request->path(),
                 ],
                 "data" => $data,
                 "errors" => $errors,
                 "duration"=> round((microtime(true) - LARAVEL_START), 3)
                 ];

      return response()->json($reply)->setstatuscode($code);

   }

   /**
    * Verifies if toke is valid
    *
    * @return json
    */
   public function token_valid(){

     try {
       \Config::set('auth.providers.users.model', \App\Admin::class);

       if (! $user = JWTAuth::parseToken()->authenticate()) {
          return ["status" => false, "error" => 'User not found'];
       }

     } catch (Exception $e) {
         if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
            return ["status" => false, "error" => 'Token is Invalid (not found)'];
         }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
            return ["status" => false, "error" => 'Token is Expired'];
         }else{
            return ["status" => false, "error" => 'No Token provided'];
         }
     }

     return ["status" => true, "user" => $user];

   }


}
