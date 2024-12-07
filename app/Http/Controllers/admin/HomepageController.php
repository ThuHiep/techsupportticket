<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomepageController extends Controller
{
    public function __construct(){

    }
    public function login(){
        return view('admin.homepage.index');
    }

}
