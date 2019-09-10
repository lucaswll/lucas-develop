<?php

namespace App\Http\Controllers;

use App\Models\City;

class HomeController extends Controller {

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }
    
    public function getStateCities($id) {
        return City::where("state_id", $id)->get();
    }
    
}
