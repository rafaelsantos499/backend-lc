<?php

namespace App\Exceptions;

use Exception;

class VerifyEmailTokenInvalid extends Exception
{
   
  protected $message = 'Email já Verificado';

  public function render(){
      
    return response()->json([
        'error' => class_basename($this),
        'message' => $this->getMessage(),
    ],400);
}
}
