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
    }//listAccessLog

    public function review()
    {
        $unimeds = DB::connection('oracle')
        ->table('RES_UNIDADE_SAUDE')
        ->leftJoin('RES_UNIDADE_IDENTIFICACAO', 'RES_UNIDADE_SAUDE.nr_sequencia', '=', 'RES_UNIDADE_IDENTIFICACAO.nr_seq_unidade_saude')
        ->select('RES_UNIDADE_SAUDE.cd_sistema_origem AS id_unimed','RES_UNIDADE_SAUDE.nm_unidade_saude AS ds_unimed')
        ->where('RES_UNIDADE_IDENTIFICACAO.cd_tipo_identificacao','=','IDUN')
        ->orderBy('RES_UNIDADE_SAUDE.nm_unidade_saude','ASC')
        ->get();

        return view('report.review',[
            'unimeds' => $unimeds,
            'bet_ini' => null,
            'bet_fim' => null,
        ]);
    }//review

    public function listReviewLog(Request $request)
    {

        $itwvWHERE  = '';
        $itwvWHERE .= (!empty($request->get('codigo_unimed'))) ? "AND id_unimed IN(". formaterUnimedCodes($request->get('codigo_unimed')) .")" : '';
        $itwvWHERE .= !empty($request->get('codigo_beneficiario')) ? "AND d.id_carteira_beneficiario LIKE '". strip_tags($request->get('codigo_beneficiario')) ."%'" : '';
        $itwvWHERE .= !empty($request->get('nome_beneficiario'))   ? "AND d.nm_beneficiario  LIKE '%". strip_tags($request->get('nome_beneficiario')) ."%'" : '';
        $itwvWHERE .= !empty($request->get('numero_sequencia'))    ? "AND a.nr_sequencia_reg LIKE '". strip_tags($request->get('numero_sequencia')) ."%'" : '';


        $logs = DB::connection('oracle')
            ->select("SELECT 
                                    d.id_unimed, 
                                    e.nm_unidade_saude ds_unimed, 
                                    d.id_carteira_beneficiario id_beneficiario, 
                                    d.nm_beneficiario, 
                                    a.id_critica, 
                                    a.dt_critica, 
                                    a.dt_atualizacao_reg, 
                                    a.nr_sequencia_reg, 
                                    a.ds_tabela, 
                                    b.id_erro, 
                                    c.ds_erro, 
                                    b.ds_coluna_erro, 
                                    b.ds_valor_erro
                                FROM integra_res_controle a, 
                                    integra_res_controle_erros b, 
                                    integra_res_erros c, 
                                    res_beneficiario d, 
                                    res_unidade_saude e
                                WHERE a.id_critica = b.id_critica 
                                    AND b.id_erro = c.id_erro 
                                    AND a.nr_sequencia_reg = d.nr_sequencia 
                                    AND e.cd_sistema_origem = d.id_unimed || '-' || d.id_unimed 
                                    {$itwvWHERE} 
                                    ORDER BY d.nm_beneficiario ASC");

        return view('report.review-list',[
            'logs' => $logs
        ]);
    }//listReviewLog


    public function transaction()
    {
        $unimeds = DB::connection('oracle')
            ->table('RES_UNIDADE_SAUDE')
            ->leftJoin('RES_UNIDADE_IDENTIFICACAO', 'RES_UNIDADE_SAUDE.nr_sequencia', '=', 'RES_UNIDADE_IDENTIFICACAO.nr_seq_unidade_saude')
            ->select('RES_UNIDADE_SAUDE.cd_sistema_origem AS id_unimed','RES_UNIDADE_SAUDE.nm_unidade_saude AS ds_unimed')
            ->where('RES_UNIDADE_IDENTIFICACAO.cd_tipo_identificacao','=','IDUN')
            ->orderBy('RES_UNIDADE_SAUDE.nm_unidade_saude','ASC')
            ->get();

        return view('report.transaction',[
            'unimeds' => $unimeds
        ]);
    }//transaction


    public function listTransactionLog(Request $request)
    {


        $itwvWHERE  = '';
        $itwvWHERE .= !empty($request->get('codigo_unimed')) ? "id_unimed IN(". formaterUnimedCodesUnique($request->get('codigo_unimed')) .")" : '';
        $itwvWHERE .= !empty($request->get('codigo_beneficiario')) ? (!empty($itwvWHERE) ? " AND " : ''). "id_beneficiario LIKE '" . strip_tags($request->get('codigo_beneficiario')) . "%'" : '';
        $itwvWHERE .= !empty($request->get('nome_beneficiario'))   ? (!empty($itwvWHERE) ? " AND " : ''). "nm_beneficiario LIKE '%" . strip_tags($request->get('nome_beneficiario')) . "%'" : '';
        $itwvWHERE .= !empty($request->get('numero_sequencia'))    ? (!empty($itwvWHERE) ? " AND " : ''). "nr_seq_transacao LIKE '" . strip_tags($request->get('numero_sequencia')) . "%'" : '';
        $itwvWHERE .= !empty($request->get('codigo_status'))       ? (!empty($itwvWHERE) ? " AND " : ''). "ds_status = '" . strip_tags($request->get('codigo_status')) . "'" : '';

        $itwvWHERE .= !empty($request->get('nome_servico'))        ? (!empty($itwvWHERE) ? " AND " : ''). "SUBSTR(nm_servico, 0, 5) IN (". formaterServicesCodes($request->get('nome_servico')) .")" : '';

        $itwvWHERE .= !empty($request->get('data_inicial'))        ? (!empty($itwvWHERE) ? " AND " : ''). "dt_log " . (trim($request->get('data_final')) != '' ? "BETWEEN TO_DATE('" . strip_tags(str_replace('%2F', '/', $request->get('data_inicial'))) . "' , 'DD/MM/RRRR') AND TO_DATE('" . strip_tags(str_replace('%2F', '/', $request->get('data_final'))) . "'" : ">= '" . strip_tags(str_replace('%2F', '/', $request->get('data_inicial'))) . "'") . ", 'DD/MM/RRRR')" : '';

         $logs = DB::connection('oracle')
             ->select("SELECT nr_seq_transacao,
								   id_unimed,
								   ds_unimed,
								   id_beneficiario,
								   nr_seq_beneficiario,
								   nm_beneficiario,
								   dt_nascimento,
								   id_mensagem_envio,
								   cd_mensagem,
								   ds_mensagem,
								   ds_status,
								   nm_servico,
								   nm_operacao,
								   TO_CHAR(dt_log, 'dd-mm-yyyy HH24:MI:SS') as dt_log,
								   (CASE WHEN xml_envio IS NOT NULL THEN 1 ELSE 0 END) xml_envio,
								   (CASE WHEN xml_retorno IS NOT NULL THEN 1 ELSE 0 END) xml_retorno
					            FROM vw_log_transacao_res_xml
					            WHERE {$itwvWHERE}
					            ORDER BY nm_beneficiario ASC");

        return view('report.transaction-list',[
            'logs' => $logs
        ]);
    }//listTransactionLog

    public function transactionXML(Request $request)
    {
        $logs = DB::connection('oracle')
            ->select("SELECT x.nr_sequencia,
				   x.nr_seq_transacao_res,
				   x.xml_envio.getclobval() || '\ #####' xml_envio,
				   x.xml_retorno.getclobval() xml_retorno
				FROM LOG_transacao_XML x
				WHERE x.nr_seq_transacao_res = {$request->get('seq')}");


        if($request->get('type') == 'RES'){
            return response()->view('report.xml-view', ['logs' => $logs[0]->xml_envio])
                ->header('Content-Type', 'text/xml');
        }else{
            return response()->view('report.xml-view', ['logs' => $logs[0]->xml_retorno])
                ->header('Content-Type', 'text/xml');
        }
    }//transactionXML

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
