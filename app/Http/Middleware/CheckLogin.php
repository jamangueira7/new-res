<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

class CheckLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

         //dd(Route::getCurrentRoute()->parameters()['user']);
        //dd(Route::getCurrentRoute()->uri);
        //dd(session('login'));
        //Limitar os não logados

        //#######ROTAS PERMITIDAS PARA USUARIOS SIMPLES#######
        $user_simple = Array(
            "user/{user}/edit",
            "user/{user}",
            "consent",
            "consent/checkstatus",
            "consent/ativacao",
        );

        //#######ROTAS PERMITIDAS PARA USUARIOS UNIMEDs#######
        $user_unimed = Array(
            "/",
            "user/{user}",
        );

        if(empty(session('login')) && Route::getCurrentRoute()->uri != "login"){
            return redirect()->route('user.login');
        }

        //Não deixa usuario logado entrar no login
        if(!empty(session('login')) && Route::getCurrentRoute()->uri == "login"){
            return redirect()->route('user.edit', session('login')['id']);
        }

        //
        if(!empty(session('login'))){
            //USER COMUM
            if(session('login')['level_id'] == 2){
                //USUÁRIO COMUM
                if(!in_array(Route::getCurrentRoute()->uri, $user_simple) &&
                    (empty(Route::getCurrentRoute()->parameters()['user'])
                        || Route::getCurrentRoute()->parameters()['user'] != session('login')['id'] )
                ){
                    //PERMITE QUE O USUSARIO SALVE O SEU PROPRIO REGISTRO
                    return redirect()->route('user.edit', session('login')['id']);

                }
            }elseif(session('login')['level_id'] == 3){
                //USER UNIMED
                if(in_array(Route::getCurrentRoute()->uri, $user_unimed)){
                    return redirect()->route('user.edit', session('login')['id']);
                }
            }
            //ADMINISTRADOR ACESSA TUDO
        }
        return $next($request);
    }
}
