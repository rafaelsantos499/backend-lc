<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\PasswordReset;
use App\Models\User;
use Illuminate\Support\Str;
use App\Events\ForgotPasswordRequest;
use Illuminate\Support\Facades\Password;
use App\Events\ForgotPassword;
use App\Http\Requests\AuthForgotPasswordRequest;

class AuthController extends Controller
{

   

    public function forgotPassword(){

        $credentials = request()->validate(['email' => 'required|email']);

          $email = $credentials['email'];       

         
        $user = User::where('email', $email)->firstOrFail();
        $token = Str::random(60);

        PasswordReset::create([
            'email' =>  $user->email,
            'token' =>  $token,
        ]);

        event(new ForgotPassword($user, $token) );
        
    }

    public function reset(AuthForgotPasswordRequest $request) {

      
        $input = $request->validated();
        $email = $input['email'];
        $password = $input['password'];
        $token = $input['token'];
        // dd($email,$password,$token);

        $passReset = PasswordReset::where('email',$email)->where('token'->$token)-first();

        if(empty($passReset)){

        }


        // $credentials = request()->validate([
        //     'email' => 'required|email',
        //     'token' => 'required|string',
        //     'password' => 'required|string'
        // ]);

        // $reset_password_status = Password::reset($credentials, function ($user, $password) {
        //     $user->password = bcrypt($password);
        //     $user->save();
        // });

        // if ($reset_password_status == Password::INVALID_TOKEN) {
        //     return response()->json(["msg" => "Invalid token provided"], 400);
        // }

        // return response()->json(["msg" => "Password has been successfully changed"]);
    }

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
