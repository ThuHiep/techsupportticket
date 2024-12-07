<?php

namespace App\Http\Controllers\guest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomepageController extends Controller
{
    public function __construct(){

    }
    public function login(){
        return view('guest.homepage.index');
    }

}
