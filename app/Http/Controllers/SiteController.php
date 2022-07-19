<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SiteController extends Controller{
    public function home(){
        $title = "Home";
        return view('site.home')->with(compact('title'));
    }

    public function sobre(){
        $title = "Sobre";
        return view('site.sobre')->with(compact('title'));
    }

    public function planos(){
        $title = "Planos";
        return view('site.planos')->with(compact('title'));
    }

    public function assistencia(){
        $title = "Assistência 24h";
        return view('site.assistencia')->with(compact('title'));
    }

}
