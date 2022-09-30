<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
   
    public function render()
    {

     
        
        return response()->json([
            'erro' => '',
            'message' => 'teste'
        ],401);
    }
}
