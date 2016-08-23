<?php

namespace App\Http\Middleware;
use Closure;
use JWTAuth;
use Exception;
class authJWT
{

  public function __construct()
  {
    #\Config::set('jwt.user' , "App\Admin");
    #\Config::set('auth.model', App\Admin::class);
    \Config::set('auth.providers.users.model', \App\Admin::class);
  }
    public function handle($request, Closure $next)
    {
        try {
          if (! $user = JWTAuth::parseToken()->authenticate()) {
            return response()->json(['user_not_found'], 404);
          }

        } catch (Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
                return response()->json(['error'=>'Token is Invalid']);
            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
                return response()->json(['error'=>'Token is Expired']);
            }else{
                return response()->json(['error'=>'Something is wrong']);
            }
        }
        return $next($request);
    }
}
