<?php

namespace App\Http\Controllers;

use App\Catalog;
use App\Admin;
use App\Page;
use App\Picture;

use Storage;
use DB;
use Illuminate\Support\Facades\Auth;
use Session;

use Illuminate\Http\Request;
use App\Http\Requests;



class AdminPagesController extends Controller
{
  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
      $this->middleware('admin');
  }

  /**
   * Show page list for a given catalog.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Catalog $catalog)
  {
      $pages = $catalog->pages()->orderBy('position', 'asc')->get();


      $last_position = $catalog->get_last_position();


      return view('admin.catalogs.pages.list', compact('pages', 'catalog', 'last_position'));
  }


  /**
   * Show page to create new catalog page.
   *
   * @return \Illuminate\Http\Response
   */
  public function new(Catalog $catalog)
  {
      return view('admin.catalogs.pages.new', compact('catalog'));
  }

  /**
   * Creates new page for the specified catalog.
   *
   * @return \Illuminate\Http\Response
   */
  public function create(Request $request, Catalog $catalog)
  {

    $page = $catalog->addPage(
              new Page($request->all())
            );

    $page->set_position();

    Session::flash('success', 'Page added!');

    return redirect('admin/catalogs/'.$catalog->id.'/pages');

  }

  /**
   * Show page to edit catalog page.
   *
   * @return \Illuminate\Http\Response
   */
  public function edit(Catalog $catalog, Page $page)
  {
      return view('admin.catalogs.pages.edit', ['catalog' => $catalog, 'page' => $page]);
  }

  /**
   * Updates the page title.
   *
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, Catalog $catalog, Page $page)
  {
    DB::table('pages')
          ->where('id', $page->id)
          ->update(['title' => $request->title]);

    Session::flash('success', 'Page title updated!');

    return redirect('admin/catalogs/'.$catalog->id.'/pages/');
  }

  /**
   * Updates the page position.
   *
   * @return \Illuminate\Http\Response
   */
  public function reposition(Request $request, Catalog $catalog, Page $page)
  {

    $old_position = $page->position;
    $page->update_position($request->position);

    Session::flash('success', 'Page '.$page->title.' repositioned from position '.$old_position.' to '.$request->position.'.');

    return redirect('admin/catalogs/'.$catalog->id.'/pages/');
  }

  /**
   * Show page to manage images on catalog page.
   *
   * @return \Illuminate\Http\Response
   */
  public function manage_images(Catalog $catalog, Page $page)
  {
    $images = $page->pictures()->get();

    return view('admin.catalogs.pages.manage_image', ['catalog' => $catalog, 'page' => $page, 'images' => $images]);
  }

  /**
   * Saves image files and adds them to the page.
   *
   * @return \Illuminate\Http\Response
   */
  public function add_images(Request $request, Catalog $catalog, Page $page)
  {

    if ($request->hasFile('file') && $request->file('file')->isValid()) {
       $file = $request->file('file');
       $title = $request->input('title');
       $description = $request->input('description');
       $filename = $file->getClientOriginalName();

       $filename = md5($filename . microtime())."_img_". $filename;

       while( Storage::exists($filename) ){
         $filename_array = explode("_img_", $filename);
         $filename_array[0] = md5($filename . microtime());
         $filename = implode ( "_img_",  $filename_array );
       }

       //dd(file_get_contents($file->getRealPath()));

       Storage::put($filename, file_get_contents($file->getRealPath()) );

       $picture = $page->addPicture(
                 new Picture(['title' => $title,
                              'description' => $description,
                              'storage_file_name' => $filename
                              ])
               );


       Session::flash('success', 'Image Added');
       return redirect('admin/catalogs/'.$catalog->id.'/pages/'.$page->id.'/images');
    }
    else {
      Session::flash('error', 'There was an error uploading the Image');
      return redirect('admin/catalogs/'.$catalog->id.'/pages/'.$page->id.'/images');
    }

  }

  /**
   * Delete page.
   *
   * @return \Illuminate\Http\Response
   */
  public function delete(Request $request, Catalog $catalog, Page $page)
  {

    // Image verification or removal to be added Here

    $page->clear_position();

    $page->delete();

    Session::flash('success', 'Page deleted!');

    return redirect('admin/catalogs/'.$catalog->id.'/pages/');
  }

}
