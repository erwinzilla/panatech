<?php

namespace App\Http\Controllers;

use App\Models\BranchServiceSABBR;
use App\Models\BranchServiceTarget;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    const blade_view = 'layout.home';
    const url_redirect ='home';
    const name = 'home';

    public function index()
    {
        $config = [
            'blade'     => self::blade_view,
            'url'       => self::url_redirect,
        ];

        // penguraian data
        $params = [
            'config'    => $config,
            'user'      => Auth::user(),
            'data'      => [
                'target'    => BranchServiceTarget::where('branch_Service', Auth::user()->branch_service)->get()->first(),
                'sabbr'     => BranchServiceSABBR::where('branch_Service', Auth::user()->branch_service)->get()->first(),
            ],
            'title'     => 'Home',
            'type'      => 'data',
        ];

        return view('layout.home.main', $params);
    }

    public function theme(Request $request, $mode)
    {
        $request->session()->put('theme', $mode);
        $theme = $request->session()->get('theme');
        return $theme == $mode ? 'success' : 'error';
    }

    public function themeIcon($mode)
    {
        return view('component.icon.theme.'.$mode);
    }
}
