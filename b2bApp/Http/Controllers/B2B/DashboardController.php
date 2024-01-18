<?php

namespace B2BApp\Http\Controllers\B2B;

use B2BApp\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(){
    //    echo "hello";die;
        $header['title'] = "B2B Dashboard";
        $header['heading']= "B2B Dashboard";
            
        return view('b2b/dashboard/dashboard')->with(['header' => $header]);
        
    }
    
}
