<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Faq;

class FaqController extends Controller
{
    public function __construct(){

    }
    public function index(){
        $template = 'admin.faq.index';
        return view('admin.dashboard.layout', compact('template'));
    }

}
