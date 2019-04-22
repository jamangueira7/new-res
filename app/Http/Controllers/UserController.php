<?php

namespace App\Http\Controllers;

use App\User;
use App\UserPermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\HelperLog;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = DB::connection('principal')
            ->table('users')
            ->get();


        return view('user.index',[
            'users' => $users
        ]);
    }//index

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $levels = DB::connection('principal')
            ->table('levels')
            ->get();
        return view('user.create', [
            'levels' => $levels
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($request->get('password') != $request->get('confirm')){
            session()->flash('error', [
                'error' => true,
                'messages' => "Erro - Os campos de senhas precisam ser iguais.",
            ]);
            return redirect()->route('user.create');
        }
        $user = new User;
        $user->name = $request->get('name');
        $user->cpf = $request->get('cpf');
        $user->email = $request->get('email');
        $user->password = md5($request->get('password'));
        $user->level_id = $request->get('level');
        $user->birth = $request->get('birth');
        $user->sex = $request->get('sex');
        $user->save();

        if(!$user->save()){
            session()->flash('error', [
                'error' => true,
                'messages' => "Erro ao atualizar usuário. Por favor informe a um administrador.",
            ]);
        }else{
            session()->flash('success', [
                'success' => true,
                'messages' => "Usuário alterado com sucesso.",
            ]);
        }

        //Log - tab
        HelperLog::gravaLog(
            'users',
            'Inclusão',
            "#id - ".$user->id
            ." #email - ".$request->get('email')
            ." #name - ".$request->get('name')
            ." #cpf - ".$request->get('cpf')
            ." #level - ".$request->get('level')
            ." #birth - ".$request->get('birth')
            ." #sex - ".$request->get('sex'),
            session('login')['id']);

        return redirect()->route('user.index');
    }

    public function login(Request $request)
    {
        $user = DB::connection('principal')
            ->table('users')
            ->select('id','name','email','cpf','password','level_id')
            ->where('email','=',$request->get('email'))
            ->whereNull('deleted_at')
            ->first();

        //dd($user, DB::getQueryLog()[0]);

        if(!empty($user) && $user->password == md5($request->get('pass'))){
            session()->flash('success', [
                'success' => true,
                'messages' => "Usuário logado.",
            ]);

            //CRIAR LOGIN
            session()->put('login', [
                'id' => $user->id,
                'email' => $user->email,
                'name' => $user->name,
                'level_id' => $user->level_id
            ]);

            //Log - tab
            HelperLog::gravaLog(
                'users',
                'Login',
                "#id - {$user->id} #email - {$user->email} #name - {$user->name} #level_id - {$user->level_id}
                ",
                $user->id);

            return redirect()->route('dashboard.index');

        }else{
            session()->flash('error', [
                'error' => true,
                'messages' => "Usuário ou senha incorretos.",
            ]);
            return redirect()->route('user.login');
        }

    }//login

    public function logout()
    {
        HelperLog::gravaLog('users','Loginout', "", session('login')['id']);
        //Log - tab
        HelperLog::gravaLog(
            'users',
            'Logout',
            "#id - ".session('login')['id']." #email - ".session('login')['email']." #name - ".session('login')['name']." #level_id - ".session('login')['level_id'],
            session('login')['id']);
        //CRIAR LOGIN
        session()->forget('login');

        return redirect()->route('user.login');
    }//logout
    /**
     * Display the specified resource.
     *
     * @param  \App\UserPermission  $userPermission
     * @return \Illuminate\Http\Response
     */
    public function show()
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\UserPermission  $userPermission
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = DB::connection('principal')
            ->table('users')
            ->where('id','=',$id)
            ->first();

        return view('user.edit',[
            'user' => $user
        ]);
    }//edit

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\UserPermission  $userPermission
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //DB::table("users")->enableQueryLog();

        $user = User::find($id);
        $user->name = $request->get('name');
        $user->cpf = $request->get('cpf');
        $user->birth = $request->get('birth');
        $user->sex = $request->get('sex');
        $user->save();

        //Log - tab
        HelperLog::gravaLog(
            'users',
            'Alteração',
            "#id - ".$id
            ." #email - ".$request->get('email')
            ." #name - ".$request->get('name')
            ." #cpf - ".$request->get('cpf')
            ." #birth - ".$request->get('birth')
            ." #sex - ".$request->get('sex'),
            $id);

        if(!$user){
            session()->flash('error', [
                'error' => true,
                'messages' => "Erro ao atualizar usuário. Por favor informe a um administrador.",
            ]);
        }else{
            session()->flash('success', [
                'success' => true,
                'messages' => "Usuário alterado com sucesso.",
            ]);
        }
        return redirect()->route('user.edit',$id);
    }//update

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\UserPermission  $userPermission
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);

        if(!$user->delete()){
            $status = 'error';
            session()->flash('error', [
                'error' => true,
                'messages' => "Erro ao excluir usuário. Por favor informe a um administrador.",
            ]);
        }else{
            $status = 'success';
            session()->flash('success', [
                'success' => true,
                'messages' => "Usuário alterado com sucesso.",
            ]);
        }

        //Log - tab
        HelperLog::gravaLog(
            'users',
            'Exclusão',
            "#id - ".$id
            ."#status - ".$status,
            session('login')['id']);

        return redirect()->route('user.index');
    }//destroy

    public function restore($id)
    {
        if(!User::withTrashed()->where("id", $id)->restore()){
            $status = 'error';
            session()->flash('error', [
                'error' => true,
                'messages' => "Erro ao ativar usuário. Por favor informe a um administrador.",
            ]);
        }else{
            $status = 'success';
            session()->flash('success', [
                'success' => true,
                'messages' => "Usuário ativado com sucesso.",
            ]);
        }

        //Log - tab
        HelperLog::gravaLog(
            'users',
            'Restauração',
            "#id - ".$id
            ."#status - ".$status,
            session('login')['id']);

        return redirect()->route('user.index');
    }//restore
}
