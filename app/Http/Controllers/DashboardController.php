<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller{
    public function index(){
        $title = "Home";
        return view('dashboards.home')->with(compact('title'));
    }
}
