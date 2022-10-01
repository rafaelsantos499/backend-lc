<?php

namespace App\Exceptions;

use Exception;

class UsuarioJaExiste extends Exception
{
    protected $message = 'Email já Cadastrado';

    public function render(){
        
      return response()->json([
          'error' => class_basename($this),
          'message' => $this->getMessage(),
      ],400);
  }
}
