<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AuthController extends Controller
{

   

    public function login(Request $request){
        $credenciais = $request->all(['email', 'password']);
        //autenticação (email, password)
         $token =  auth('api')->attempt($credenciais);
        //retorna um token
        if($token){
             return response()->json(['token' => $token]);
        }else {
            return response()->json(['erro' => 'Usuário ou senha inválida'], 403);
        }      
          
    }

 

    public function store(Request $request){     

    $regras = [
        "name" => 'required',
        "email" => 'required|unique:users',
        "password"=> 'required'
    ];

    $feedback = [
        'required' => 'O campo :attribute é obrigatório',
        'email.unique' => 'esse email já existe'
        
    ];

    $request->validate($regras, $feedback);


    $dateUsuario = [
        "name" => $request->input('name') , 
        "email" => $request->input('email') ,
        "password" => bcrypt($request->input('password')),
    ] ; 

      $usuario =   User::create($dateUsuario) ; 

      return response()->json($usuario, 200);
    
    }

    public function logout(){
        auth('api')->logout();
        return response()->json(['msg' => 'Logout foi realizado com sucesso!']);
    }

   public function refresh(){
        $token = auth('api')->refresh();
        return response()->json(['token' => $token]);
    }

    public function me(){

        return response()->json(auth()->user()) ;
    }

}
