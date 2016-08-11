<?php

namespace App\Http\Controllers;

use App\Catalog;
use App\Admin;
use App\Page;

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
   * Delete page.
   *
   * @return \Illuminate\Http\Response
   */
  public function delete(Request $request, Catalog $catalog, Page $page)
  {

    // Image verification or removal to be added Here

    $page->clear_position();

    DB::table('pages')
          ->where('id', $page->id)
          ->delete();

    Session::flash('success', 'Page deleted!');

    return redirect('admin/catalogs/'.$catalog->id.'/pages/');
  }

}
