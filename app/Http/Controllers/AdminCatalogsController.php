<?php

namespace App\Http\Controllers;

use App\Catalog;
use App\Admin;

use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Requests;


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

    return redirect('admin/catalogs');

  }

}
