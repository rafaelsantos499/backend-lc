<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Resources\UserResource;
use App\Http\Requests\AlterarSenhaRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class MeController extends Controller
{
    public function index(){
     

        return (new UserResource(auth()->user()));
    }

    public function update(AlterarSenhaRequest $request){

        $input = $request->validated();        
        $usuario = Auth::user();
        $newPassword = bcrypt($input['new_password']);
        $oldPassword = $input['old_password'];

    //    dd($request->new_password);
       
    // dd(!Hash::check($oldPassword, $usuario->password));
    if(!Hash::check($oldPassword, $usuario->password)){
        
        return response()->json([
            'messsage' => 'Senha Antiga Invalida.',
       ]);;
    }
        
        $usuario->password = $newPassword;

        $usuario->save(); 

    //   if (Hash::check($newPassword, $usuario->password)){
    //     (('teste'));
    //   }




      return response()->json([
           'messsage' => 'Sua Senha foi alterada com sucesso',
      ]);
    

   


      

    }
}
