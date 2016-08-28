<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Admin;
use App\Catalog;
use App\Page;

use App\Http\Requests;
use Exception;
use Validator;

use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class ApiV1PagesController extends ApiV1Controller
{

  /**
   * Retrieve all pages in a catalog
   *
   * @return json
   */
  public function retrieve_all_in_catalog(Request $request, $id)
  {
    $data = array();
    $errors = array();

    if ( Catalog::where('id', $id)->exists() ){

      $catalog = Catalog::find($id);

      $pages = $catalog->pages()->get();

      if ( !$pages->isEmpty() ){
        foreach($pages as $page){
          $new_element["id"] = $page->id;
          $new_element["title"] = $page->title;
          $new_element["description"] = $page->description;
          $new_element["created_at"] = $page->created_at->toDateTimeString();
          $new_element["updated_at"] = $page->updated_at->toDateTimeString();

          array_push ($data, $new_element);
        }
      }

      $extras = array();
      $extras["meta"] = array();
      $extras["meta"]["limit"] = 30;
      $extras["meta"]["offset"] = 0;
      $extras["meta"]["total"] = $pages->count();

      $success = 1;
      $code = 200;

    }else{
      $errors["message"] = "The resource that matches ID:".$id." was not found.";
      $success = 0;
      $code = 404;
    }

    return $this->build_reply($request, $success, $code, $data, $errors, $extras );
  }

  /**
   * Retrieve one page
   *
   * @return json
   */
  public function retrieve(Request $request, $id)
  {

    $data = array();
    $errors = array();

    if ( Page::where('id', $id)->exists() ){

      $page = Page::find($id);

      $data["id"] = $page->id;
      $data["title"] = $page->title;
      $data["description"] = $page->description;
      $data["created_at"] = $page->created_at->toDateTimeString();
      $data["updated_at"] = $page->updated_at->toDateTimeString();

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
   * Create a Page
   *
   * @return json
   */
  public function create(Request $request, $id)
  {
    $data = array();
    $errors = array();
    if (  $request->has('title') &&
          (strlen($request->title) <= 64) &&
          $request->has('description') &&
          Catalog::where('id', $id)->exists()
       ){

      $catalog = Catalog::find($id);

      $page = $catalog->addPage(  new Page([
                                      'title' => $request->title,
                                      'description' => $request->description
                                  ])
                                );

      $page->set_position();

      $success = 1;
      $code = 201;
      $data = ["id" => $page->id];

    }else if(!Catalog::where('id', $id)->exists()){
      $errors = [ "message" => "The catalog resource that matches ID:".$id." was not found.",
                  "code" => 403001
                ];
      $success = 0;
      $code = 403;
    }else{
      $errors["message"] = "The request parameters are incorrect, please make sure to follow the HiCat document.";
      $errors["code"] = 400002;
      $errors["validation"] = array();

      if (!$request->has('title')){
        $errors["validation"]['title']['key'] = 'required';
        $errors["validation"]['title']['message'] = 'The title field is required.';
      }else if( strlen($request->title) > 64 ){
        $errors["validation"]['title']['key'] = 'max:64';
        $errors["validation"]['title']['message'] = 'The name may not be greater than 64 characters.';
      }

      $success = 0;
      $code = 400;
    }
    return $this->build_reply($request, $success, $code, $data, $errors );
  }


  /**
   * Update Page
   *
   * @return json
   */
  public function update(Request $request, $id)
  {
    $data = array();
    $errors = array();


    if (  Page::where('id', $id)->exists() &&
          ( ( $request->has('title') && (strlen($request->title) <= 64) )  ||
            !$request->has('title')
          ) &&
          ( $request->has('title') || $request->has('description') )
       ){

      $page = Page::find($id);

      if ( $request->has('title') ){
        $page->title = $request->title;
      }

      if ( $request->has('description') ){
        $page->description = $request->description;
      }

      $page->save();

      $data = ["updated" => 1];
      $success = 1;
      $code = 201;
      $data = ["id" => $page->id];

    }else{
      //$data = ["updated" => 0];

      if ( !Page::where('id', $id)->exists() ){
        $errors = [ "message" => "The resource that matches ID:".$id." does not found.",
                    "code" => 403001
                  ];
        $code = 403;
      }else if($request->has('title') && strlen($request->title) > 64 ){
        $errors["message"] = "The request parameters are incorrect, please make sure to follow the HiCat document.";
        $errors["code"] = 400002;
        $errors["validation"] = array();

        $errors["validation"]['title']['key'] = 'max:64';
        $errors["validation"]['title']['message'] = 'The name may not be greater than 64 characters.';
        $code = 400;
      }else if( !$request->has('title') && !$request->has('description') ){
        //$errors["message"] = "The request parameters are incorrect, please make sure to follow the HiCat document.";
        $errors["message"] = "The title or the description need to be provided to update successfully.";
        $code = 400;
      }

      $success = 0;

    }
    return $this->build_reply($request, $success, $code, $data, $errors );
  }


  /**
   * Reposition Page
   *
   * @return json
   */
  public function reposition(Request $request, $id)
  {
    $data = array();
    $errors = array();
    if ( !$request->has('position') ){
      $errors["message"] = "The request parameters are incorrect.";
      // $errors["code"] = 400002;
      $errors["validation"] = array();

      $errors["validation"]['position']['key'] = 'required';
      $errors["validation"]['position']['message'] = 'The position field is required.';
      $success = 0;
      $code = 400;

    }else if( !Page::where('id', $id)->exists() ){
      //$data["repositioned"] = 0;
      $errors["message"] = "The resource that matches ID:".$id." does not found.";
      $errors["code"] = 403001;
      $success = 0;
      $code = 403;

    }else{

      $page =  Page::find($id);

      $catalog = $page->catalog()->get()->first();

      $max_position = $catalog->pages()->count(); 

      if ( (0 < $request->position) && ($request->position <= $max_position) ){
        $old_position = $page->position;
        $page->update_position($request->position);

        $data["repositioned"] = 1;
        $success = 1;
        $code = 200;
      }else{
        //$data["repositioned"] = 0;
        $errors["message"] = "The position must be between 0 and ".$max_position."(the number of pages on the catalog)";
        $errors["code"] = 403001;
        $success = 0;
        $code = 403;
      }

    }
    return $this->build_reply($request, $success, $code, $data, $errors );
  }


  /**
   * Delete Page
   *
   * @return json
   */
  public function delete(Request $request, $id)
  {
    $data = array();
    $errors = array();
    if ( Page::where('id', $id)->exists() ){

      $page =  Page::find($id);
      $page->delete();

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
