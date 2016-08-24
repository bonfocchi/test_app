<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Admin;

use App\Http\Requests;

use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class ApiV1UsersController extends ApiV1Controller
{

  /**
   * Check if user exists
   *
   * @return json
   */
  public function check_user(Request $request, $id)
  {
    $data = array();
    $errors = array();
    if ( Admin::where('id', $id)->exists() ){
      $data["exists"] = 1;
      $success = 1;
      $code = 200;

    }else{
      //$data["exists"] = 0;
      $errors["message"] = "User not found";
      $success = 0;
      $code = 404;
    }
    return $this->build_reply($request, $success, $code, $data, $errors );
  }

  /**
   * Create a new user
   *
   * @return json
   */
  public function create_user(Request $request)
  {
    $data = array();
    $errors = array();
    if (  $request->has('hubsynch_id') &&
          is_numeric($request->hubsynch_id) &&
          $request->has('email') &&
          !Admin::where('email', $request->email)->exists() &&
          $request->has('password')
       ){

      $user = Admin::create([
          'email' => $request->email,
          'password' => bcrypt($request->password),
          //'hubsynch_id' => $request->hubsynch_id
      ]);

      $data["users"] = ["id" => $user->id];
      $data["subscriptions"] = ["id" => '?'];

      $success = 1;
      $code = 200;

    }else{
      //$data["exists"] = 0;
      $errors["message"] = "The request parameters are incorrect, please make sure to follow the HiCat document.";
      $errors["code"] = 400002;
      $errors["validation"] = array();

      if (!$request->has('hubsynch_id')){
        $errors["validation"]['hubsynch_id']['key'] = 'required';
        $errors["validation"]['hubsynch_id']['message'] = 'The hubsynch_id field is required.';
      }else if( !is_numeric($request->hubsynch_id) ){
        $errors["validation"]['hubsynch_id']['key'] = 'integer';
        $errors["validation"]['hubsynch_id']['message'] = 'The hubsynch id must be an integer.';
      }

      if (!$request->has('email')){
        $errors["validation"]['email']['key'] = 'required';
        $errors["validation"]['email']['message'] = 'The email field is required.';
      }else if( Admin::where('email', $request->email)->exists() ){
        $errors["validation"]['email']['key'] = 'invalid';
        $errors["validation"]['email']['message'] = 'Provided email is already in use.';
      }

      if (!$request->has('password')){
        $errors["validation"]['password']['key'] = 'required';
        $errors["validation"]['password']['message'] = 'The password field is required.';
      }

      $success = 0;
      $code = 400;
    }
    return $this->build_reply($request, $success, $code, $data, $errors );
  }

  /**
   * Delete user
   *
   * @return json
   */
  public function delete_user(Request $request, $id)
  {
    $data = array();
    $errors = array();
    if ( Admin::where('id', $id)->exists() ){

      $user =  Admin::find($id);
      $user->delete();

      $data["deleted"] = 1;
      $success = 1;
      $code = 200;

    }else{
      //$data["deleted"] = 0;
      $errors["message"] = "The resource that matches ID:".$id." does not found.";
      $errors["code"] = 403001;
      $success = 0;
      $code = 403;
    }
    return $this->build_reply($request, $success, $code, $data, $errors );
  }

}
