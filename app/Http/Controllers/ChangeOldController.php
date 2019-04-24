<?php

namespace App\Http\Controllers;

use App\Level;
use App\User;
use App\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChangeOldController extends Controller
{
    /**
     * Essa classe será usada apenas para migração do banco antigo para um novo banco.
     */
    public function index()
    {    ini_set('max_execution_time', '3000000');
        $resp = "#########INICIADO############" . "<br />";
        $levels = DB::connection('remoto')
            ->table('nivel')->get();

        $resp .= "####################### Acionando na tabela LEVELS #######################" . "<br />";

        foreach ($levels as $level){
            $newLevel= new Level();
            $newLevel->description = $level->descricao;
            $newLevel->save();
            $resp .= "=>>>>Tabela nivel codigo - {$level->codigo} gravou na Tabela levels com o codigo - {$newLevel->id} no novo banco" . "<br />";
        }

        $resp .= "####################### Acionando na tabela USERS #######################" . "<br />";
        $users = DB::connection('remoto')
            ->table('usuarios')->get();

        foreach ($users as $user){
            $newUser = new User();
            $newUser->name = $user->nome;
            $newUser->email = $user->email;
            $newUser->cpf = $user->cpf;
            $newUser->birth = $user->nascimento;
            $newUser->password = $user->senha;
            $newUser->sex = 'M';
            $newUser->unimed = '0';
            $newUser->level_id = 1;
            $newUser->save();
            $resp .= "->Tabela usuario codigo - {$user->codigo} gravou na Tabela users com o codigo - {$newUser->id} no novo banco" . "<br />";
            $resp .= $this->gravarLogs($user->codigo, $newUser->id);
        }

        $resp .= "#########FINALIZANDO############" . "<br />";

        return $resp;
    }//index

    private function gravarLogs($old_id, $new_id)
    {
        $resp = "####################### Acionando na tabela LOGS #######################" . "<br />";
        $logs = DB::connection('remoto')
            ->table('logs')
            ->join('logs_tipo_acao', 'logs.codigo_tipo_acao', '=', 'logs_tipo_acao.codigo')
            ->select('logs.*', 'logs_tipo_acao.descricao')
            ->where('codigo_usuario', '=',$old_id)
            ->get();

        foreach ($logs as $log){
            $newLog = new Log();
            $newLog->table = !empty($log->tabela) ? $log->tabela : "JOIN";
            $newLog->sql_code = $log->codigo_sql;
            $newLog->action = $log->descricao;
            $newLog->user_id = $new_id;
            $newLog->save();
            $resp .= "--->Tabela logs codigo - {$log->codigo} gravou na Tabela logs com o codigo - {$newLog->id} no novo banco" . "<br />";
        }
        return $resp;
    }//gravarLogs

}
