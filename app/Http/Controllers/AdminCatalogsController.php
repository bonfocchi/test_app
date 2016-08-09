<?php

namespace App\Http\Controllers;

use App\Catalog;
use App\Admin;

use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Requests;
use Session;



class AdminCatalogsController extends Controller
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
   * Show the application dashboard.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
      $catalogs = DB::table('catalogs')->get();

      return view('admin.catalogs.list', compact('catalogs'));
  }

  public function new()
  {
      return view('admin.catalogs.new');
  }

  public function create(Request $request)
  {
    $admin = Auth::guard('admin')->user();

    $admin->addCatalog(
      new Catalog($request->all())
    );

    Session::flash('success', 'Catalog created!');

    return redirect('admin/catalogs');

  }

  public function edit(Catalog $catalog)
  {
      return view('admin.catalogs.edit', ['catalog' => $catalog]);
  }

  public function update(Request $request, Catalog $catalog)
  {
    DB::table('catalogs')
          ->where('id', $catalog->id)
          ->update(['name' => $request->name]);

    Session::flash('success', 'Catalog updated!');

    return redirect('admin/catalogs/'.$catalog->id);
  }

}
