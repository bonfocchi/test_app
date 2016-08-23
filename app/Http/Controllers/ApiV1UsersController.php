<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Requests;

use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class ApiV1UsersController extends ApiV1Controller
{

  /**
   * Delete Image
   *
   * @return \Illuminate\Http\Response
   */
  public function api_test(Request $request)
  {
    //return response()->json(['loggedin' => 'yes', 'user' => JWTAuth::parseToken()->toUser() ]);
    dd($request);
  }
}
