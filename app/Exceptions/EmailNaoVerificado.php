<?php

namespace App\Exceptions;

use Exception;

class EmailNaoVerificado extends Exception
{
    protected $message = 'Esse Email não esta verificado.';

    public function render(){
        
      return response()->json([
          'error' => class_basename($this),
          'message' => $this->getMessage(),
      ],400);
  }
}
