<?php

namespace App\Http\Controllers;


use Ido\Tools\Dto\DtoBuilder;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function welcome(Request $request)
    {
        return view('welcome');
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function home(Request $request)
    {
        return view('home');
    }

    public function testDto(){
        return $this->dto(['key1' => 123, 'key2' => 456,],
            function (DtoBuilder $dtoBuilder) {
                $dtoBuilder->add('key1');
                $dtoBuilder->add('key2');
            });
    }
}
