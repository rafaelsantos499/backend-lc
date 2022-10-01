<?php

namespace App\Exceptions;

use Exception;

class ResetPasswordTokenInvalidException extends Exception
{
    protected $message = 'Token Invalid.';

    public function reder(){

        return response()->json([
          'error' => class_basename($this),
          'message' => $this->getMessage(),
        ],400);
        
    }
}
