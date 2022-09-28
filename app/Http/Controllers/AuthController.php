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
       $novoUsuario = User::create($request->all());
       dd($novoUsuario);
         
        return 'cheguei';
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
