<?php

namespace App\Http\Controllers;

use App\UserPermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = DB::connection('principal')
            ->table('users')
            ->get();
        return view('report.index',[
            'users' => $user
        ]);
    }//index

    public function listAccessLog(Request $request)
    {

        $logs = DB::connection('principal')
            ->table('users')
            ->join('logs', 'users.id', '=', 'logs.user_id')
            ->select( 'users.name', 'users.email', 'logs.created_at', 'logs.action')
            ->where('logs.user_id','=', '1')
            ->whereBetween('logs.created_at', [
                convDateMySQLforDateTime($request->get('data_inicial')),
                convDateMySQLforDateTime($request->get('data_final'), false)
            ])
            ->orderBy('logs.created_at','asc')
            ->get();
        return view('report.access-list',[
            'logs' => $logs
        ]);
    }//review

    public function review()
    {
        return view('report.review');
    }//review

    public function parameters()
    {
        return view('report.parameters');
    }//parameters


    public function transaction()
    {
        return view('report.transaction');
    }//transaction


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
