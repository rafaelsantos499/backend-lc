<?php

namespace App\Exceptions;

use Exception;

class ResetPasswordTokenInvalidException extends Exception
{
    protected $message = 'Token Invalido';

    public function render(){
        
      return response()->json([
          'error' => class_basename($this),
          'message' => $this->getMessage(),
      ],401);
  }
}
