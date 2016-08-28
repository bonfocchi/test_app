<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Admin;
use App\Catalog;

use App\Http\Requests;
use Exception;
use Validator;

use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class ApiV1CatalogsController extends ApiV1Controller
{

  /**
   * Retrieve all catalogs
   *
   * @return json
   */
  public function retrieve_all(Request $request)
  {
    $data = array();
    $errors = array();

    $admin = JWTAuth::parseToken()->authenticate();
    $catalogs = $admin->catalogs()->get();

    if ( !$catalogs->isEmpty() ){
      foreach($catalogs as $catalog){
        $new_element["id"] = $catalog->id;
        $new_element["admin_id"] = $catalog->admin_id;
        $new_element["name"] = $catalog->name;
        $new_element["created_at"] = $catalog->created_at->toDateTimeString();
        $new_element["updated_at"] = $catalog->updated_at->toDateTimeString();

        array_push ($data, $new_element);
      }
    }

    $extras = array();
    $extras["meta"] = array();
    $extras["meta"]["limit"] = 30;
    $extras["meta"]["offset"] = 0;
    $extras["meta"]["total"] = $catalogs->count();

    $success = 1;
    $code = 200;

    return $this->build_reply($request, $success, $code, $data, $errors, $extras );
  }

  /**
   * Retrieve one catalog
   *
   * @return json
   */
  public function retrieve(Request $request, $id)
  {

    $data = array();
    $errors = array();

    if ( Catalog::where('id', $id)->exists() ){

      $catalog = Catalog::find($id);

      $data["id"] = $catalog->id;
      $data["admin_id"] = $catalog->admin_id;
      $data["name"] = $catalog->name;
      $data["created_at"] = $catalog->created_at->toDateTimeString();
      $data["updated_at"] = $catalog->updated_at->toDateTimeString();

      $success = 1;
      $code = 200;

    }else{
      $errors["message"] = "The resource that matches ID:".$id." was not found.";
      $success = 0;
      $code = 404;

    }

    return $this->build_reply($request, $success, $code, $data, $errors );
  }


  /**
   * Create a Catalog
   *
   * @return json
   */
  public function create(Request $request)
  {
    $data = array();
    $errors = array();
    if (  $request->has('name') &&
          (strlen($request->name) <= 64)
       ){

      $catalog = Catalog::create(['name' => $request->name]);

      $success = 1;
      $code = 201;
      $data = ["id" => $catalog->id];

    }else{
      $errors["message"] = "The request parameters are incorrect, please make sure to follow the HiCat document.";
      $errors["code"] = 400002;
      $errors["validation"] = array();

      if (!$request->has('name')){
        $errors["validation"]['name']['key'] = 'required';
        $errors["validation"]['name']['message'] = 'The name field is required.';
      }else if( strlen($request->name) > 64 ){
        $errors["validation"]['name']['key'] = 'max:64';
        $errors["validation"]['name']['message'] = 'The name may not be greater than 64 characters.';
      }

      $success = 0;
      $code = 400;
    }
    return $this->build_reply($request, $success, $code, $data, $errors );
  }

  /**
   * Update Catalog
   *
   * @return json
   */
  public function update(Request $request, $id)
  {
    $data = array();
    $errors = array();


    if (  Catalog::where('id', $id)->exists() &&
          ( $request->has('name') && (strlen($request->name) <= 64) )
       ){

      $catalog = Catalog::find($id);
      $catalog->name = $request->name;
      $catalog->save();

      $data = ["updated" => 1];
      $success = 1;
      $code = 201;
      $data = ["id" => $catalog->id];

    }else{
      //$data = ["updated" => 0];

      if ( !Catalog::where('id', $id)->exists() ){
        $errors = [ "message" => "The resource that matches ID:".$id." does not found.",
                    "code" => 403001
                  ];
        $code = 403;
      }else if (!$request->has('name')){
        $errors["message"] = "The request parameters are incorrect, please make sure to follow the HiCat document.";
        $errors["code"] = 400002;
        $errors["validation"] = array();
        $errors["validation"]['name']['key'] = 'required';
        $errors["validation"]['name']['message'] = 'The name field is required.';
        $code = 400;
      }else if( strlen($request->name) > 64 ){
        $errors["message"] = "The request parameters are incorrect, please make sure to follow the HiCat document.";
        $errors["code"] = 400002;
        $errors["validation"] = array();
        $errors["validation"]['name']['key'] = 'max:64';
        $errors["validation"]['name']['message'] = 'The name may not be greater than 64 characters.';
        $code = 400;
      }

      $success = 0;

    }
    return $this->build_reply($request, $success, $code, $data, $errors );
  }

  /**
   * Delete Catalog
   *
   * @return json
   */
  public function delete(Request $request, $id)
  {
    $data = array();
    $errors = array();
    if ( Catalog::where('id', $id)->exists() ){

      $catalog =  Catalog::find($id);
      $catalog->delete();

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
