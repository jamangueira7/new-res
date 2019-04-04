<?php

namespace App\Http\Controllers;

use App\UserPermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $unimeds = DB::connection('oracle')
            ->table('RES_UNIDADE_SAUDE')
            ->leftJoin('RES_UNIDADE_IDENTIFICACAO', 'RES_UNIDADE_SAUDE.nr_sequencia', '=', 'RES_UNIDADE_IDENTIFICACAO.nr_seq_unidade_saude')
            ->select('RES_UNIDADE_SAUDE.cd_sistema_origem AS id_unimed','RES_UNIDADE_SAUDE.nm_unidade_saude AS ds_unimed')
            ->where('RES_UNIDADE_IDENTIFICACAO.cd_tipo_identificacao','=','IDUN')
            ->orderBy('RES_UNIDADE_SAUDE.nm_unidade_saude','ASC')
            ->get();


        return view('dashboard.index', [
            'unimeds' => $unimeds
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\UserPermission  $userPermission
     * @return \Illuminate\Http\Response
     */
    public function show(UserPermission $userPermission)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\UserPermission  $userPermission
     * @return \Illuminate\Http\Response
     */
    public function edit(UserPermission $userPermission)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\UserPermission  $userPermission
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserPermission $userPermission)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\UserPermission  $userPermission
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserPermission $userPermission)
    {
        //
    }
}
