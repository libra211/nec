<?php

namespace App\Http\Controllers;

class LegalController extends Controller
{
    public function privacyPolicy()
    {
        return view('legal.privacy-policy');
    }

    public function termsOfUse()
    {
        return view('legal.terms-of-use');
    }

    public function accessibility()
    {
        return view('legal.accessibility');
    }
}
