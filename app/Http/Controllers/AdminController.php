<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

   /**
    * Show the admin area dashboard.
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
        return view('admin.dashboard');
    }
}
