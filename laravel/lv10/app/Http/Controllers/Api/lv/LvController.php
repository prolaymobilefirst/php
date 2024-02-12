<?php

namespace App\Http\Controllers\lv;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LvController extends Controller
{
    public function index(){
        return view('pages/lv/index');
    }


    public function onRunArtisanCommands(Request $request){

    }
}
