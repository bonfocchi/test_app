<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Subscription;

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
    if ( User::where('id', $id)->exists() ){
      $data["exists"] = 1;
      $success = 1;
      $code = 200;

    }else{
      $data["exists"] = 0;
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
    $admin = JWTAuth::parseToken()->authenticate();

    if (  $request->has('hubsynch_id') &&
          is_numeric($request->hubsynch_id) &&
          $request->has('email') &&
          $request->has('password')
       ){
        $exists_user_with_hubsynch_id = User::where('hubsynch_id', $request->hubsynch_id)->exists();
        $exists_user_with_email = User::where('email', $request->email)->exists();

        if ( !$exists_user_with_hubsynch_id &&
             !$exists_user_with_email
           ){
          // New user
          $user = User::create([
              'email' => $request->email,
              'password' => bcrypt($request->password),
              'hubsynch_id' => $request->hubsynch_id
          ]);

        }else if(
            !$exists_user_with_hubsynch_id &&
             $exists_user_with_email &&
            (User::where('email', $request->email)->first()->hubsynch_id == 0)
        ){
          // Exists user with email and without hubsynch_id
          $user = User::where('email', $request->email)->first();
          $user->hubsynch_id = $request->hubsynch_id;
          $user->save();
        }else if(
            !$exists_user_with_hubsynch_id &&
             $exists_user_with_email &&
            (User::where('email', $request->email)->first()->hubsynch_id != 0)
        ){
          // Exists user with email and with different hubsynch_id
          $errors["message"] = "Conflict - Email already being used by user with a different hubsynch_id.";
          $success = 0;
          $code = 401;
          return $this->build_reply($request, $success, $code, $data, $errors );
        }else if(
             $exists_user_with_hubsynch_id &&
            !$exists_user_with_email
        ){
          // Exists user with hubsynch_id and with different email
          $errors["message"] = "Conflict - hubsynch_id already being used by user with a different email.";
          $success = 0;
          $code = 401;
          return $this->build_reply($request, $success, $code, $data, $errors );

        }else{
          // Exists user provided hubsynch_id and email
          $user = User::where('hubsynch_id', $request->hubsynch_id)->first();

          if ( Subscription::where(['user_id' => $user->id, 'admin_id' => $admin->id])->exists() ){
            $errors["message"] = "Duplicates - User and Subscription already exist.";
            $success = 0;
            $code = 401;
            return $this->build_reply($request, $success, $code, $data, $errors );
          }
        }


        $subscription = $user->addSubscription( new Subscription(['admin_id' => $admin->id]) );

        $data["users"] = ["id" => $user->id];
        $data["subscriptions"] = ["id" => $subscription->id];

        $success = 1;
        $code = 200;

    }else{
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
    if ( User::where('id', $id)->exists() ){

      $user =  User::find($id);
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
