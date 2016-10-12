<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SitesearchController extends Controller
{
    public function loadPage(Request $request)
    {
        return view('widget.sitesearch')
            ->with('title', trans('titles.sitesearch'))
            ->with('site', $request->input('site', ''))
            ->with('navbarFocus', 'dienste');
    }
}
