<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use App\Log;

class HelperLog
{
    static function gravaLog($table, $action, $sql,$user_id)
    {
        $log = new Log();

        $log->table = $table;
        $log->action = $action;
        $log->sql_code = $sql;
        $log->user_id = $user_id;
        $log->save();
    }
}
