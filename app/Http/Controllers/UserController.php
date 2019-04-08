<?php

namespace App\Http\Controllers;

use App\User;
use App\UserPermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $user->password = $request->get('password');
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

        return redirect()->route('user.index');
    }

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
        $user = User::find($id);
        $user->name = $request->get('name');
        $user->cpf = $request->get('cpf');
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

        return redirect()->route('user.index');
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
            session()->flash('error', [
                'error' => true,
                'messages' => "Erro ao excluir usuário. Por favor informe a um administrador.",
            ]);
        }else{
            session()->flash('success', [
                'success' => true,
                'messages' => "Usuário alterado com sucesso.",
            ]);
        }
        return redirect()->route('user.index');
    }//destroy

    public function restore($id)
    {
        if(!User::withTrashed()->where("id", $id)->restore()){
            session()->flash('error', [
                'error' => true,
                'messages' => "Erro ao ativar usuário. Por favor informe a um administrador.",
            ]);
        }else{
            session()->flash('success', [
                'success' => true,
                'messages' => "Usuário ativado com sucesso.",
            ]);
        }
        return redirect()->route('user.index');
    }//restore
}
