<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\PasswordReset;
use App\Models\User;
use Illuminate\Support\Str;
use App\Events\ForgotPasswordRequest;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Auth;


   //request

use App\Http\Requests\AuthForgotPasswordRequest;
use App\Http\Requests\AuthLoginRequest;
use App\Http\Requests\AuthRegisterRequest;
use App\Http\Requests\AuthVerifyEmailRequest;
use App\Http\Requests\AlterarSenhaRequest;


//mesagens de error

use App\Exceptions\ResetPasswordTokenInvalidException;
use App\Exceptions\LoginInvalidException;
use App\Exceptions\UsuarioJaExiste;
use App\Exceptions\VerifyEmailTokenInvalid;
use App\Exceptions\EmailNaoVerificado;



// Resources

use App\Http\Resources\UserResource;

// Events

use App\Events\ForgotPassword;
use App\Events\UserRegistered;



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

        $passReset = PasswordReset::where('email',$email)->where('token' , $token)->first();

        if(empty($passReset)){
              throw new ResetPasswordTokenInvalidException();              
        }

        $user = User::where('email' , $email)->firstOrFail();
        $user->password = bcrypt($password);
        $user->save();


        PasswordReset::where('email', $email)->delete();


        return '';

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

    public function login(AuthLoginRequest $request){

        $input = $request->validated();
        $email = $input['email'];
       
        $login = [
            'email' => $input['email'],
            'password' => $input['password'],
        ];

        // dd(auth('api')->attempt($login));

        
        if(!$token = auth('api')->attempt($login)){
           throw new LoginInvalidException();
        };

        // if(User::where('email' , $email)->firstOrFail()->email_verified_at === null){
           
        //     throw new  EmailNaoVerificado();
            
        // }       
    
   
        $dateToken =  [
            'token' => $token,
            'token_type' => 'Bearer'
        ];  

      

        return (new UserResource(User::where('email' , $email)->firstOrFail()))->additional($dateToken);

        // return   new UserResource(auth()->user());
        // $credenciais = $request->all(['email', 'password']);
        // //autentica????o (email, password)
        //  $token =  auth('api')->attempt($credenciais);
        // //retorna um token
        // if($token){
        //      return response()->json(['token' => $token]);
        // }else {
        //     return response()->json(['erro' => 'Usu??rio ou senha inv??lida'], 403);
        // }      
          
    }


    public function novoUsuario(AuthRegisterRequest $request){     


        $input =  $request->validated();
        $name = $input['name'];
        $email = $input['email'];
        $password = $input['password'];

        $user = User::where('email', $email)->exists();

       if($user){
         throw new UsuarioJaExiste();
       };


       $dateUsuario = [
        "name" => $name  , 
        "email" => $email ,
        "password" => bcrypt($password),
        'confimation_token' => Str::random(60),
    ] ; 
       
       $user = User::create($dateUsuario);

       event(new UserRegistered($user));
      

      return new UserResource($user);

       

    // $regras = [
    //     "name" => 'required',
    //     "email" => 'required|unique:users',
    //     "password"=> 'required'
    // ];

    // $feedback = [
    //     'required' => 'O campo :attribute ?? obrigat??rio',
    //     'email.unique' => 'esse email j?? existe'
        
    // ];

    // $request->validate($regras, $feedback);


    // $dateUsuario = [
    //     "name" => $request->input('name') , 
    //     "email" => $request->input('email') ,
    //     "password" => bcrypt($request->input('password')),
    // ] ; 

    //   $usuario =   User::create($dateUsuario) ; 

    //   return response()->json($usuario, 200);
    
    }

    public function verificarEmail(AuthVerifyEmailRequest $request){
        
        $input = $request->validated();
        $token = $input['token'];


        $user = User::where('confimation_token', $token)->first();

        if(empty($user)){
            //se esse cara estiver vazio

             throw new VerifyEmailTokenInvalid();
        }

        $user->confimation_token = null;
        $user->email_verified_at = now();
        $user->save();
        
        return new UserResource($user);
    
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
