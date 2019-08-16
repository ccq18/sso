<?php

namespace App\Http\Controllers;

use Auth;
use Ido\Tools\Dto\DtoService;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    public function guard()
    {
        return Auth::guard();
    }


    public function response($data, $code=200, $message='success')
    {
        return response()->json(['data' => $data,'code'=>$code,'message'=>$message]);

    }



    public function dto($data, $dtoProvider)
    {
        $data = resolve(DtoService::class)->transfer($data, $dtoProvider);
        return $this->response($data);
    }

    // public function response($data, $code=200, $message='success')
    // {
    //     return response()->json(['data' => $data,'code'=>$code,'message'=>$message]);
    //
    // }
    public function redirectTo()
    {
        return $this->redirectTo;
    }

    function flash($title, $message)
    {
        session()->flash('flash_message', [
            // 'type'    => $type,
            'title'   => $title,
            'message' => $message
        ]);
    }
}
