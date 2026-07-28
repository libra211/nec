<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GisController extends Controller
{
    public function map()
    {
        return view('gis.map');
    }
}
