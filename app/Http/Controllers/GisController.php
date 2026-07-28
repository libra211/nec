<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class GisController extends Controller
{
    public function map()
    {
        $states = DB::table('nec_states')->where('status', 'active')->orderBy('name')->get();
        return view('gis.map', compact('states'));
    }
}
