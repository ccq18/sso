<?php

namespace App\Http\Controllers;

use Ccq18\Auth\AuthHelper;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('home');
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function authToken(Request $request)
    {
        return resolve(AuthHelper::class)->getUserByToken($request->get('token'));
    }
}
