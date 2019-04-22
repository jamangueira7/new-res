<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use App\Log;
use App\Consent;

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

    static function gravaConsent($code, $archive, $status, $recipient, $unimed, $user_id)
    {
        $consent = new Consent();

        $consent->code = $code;
        $consent->archive = $archive;
        $consent->status = $status;
        $consent->recipient = $recipient;
        $consent->unimed = $unimed;
        $consent->user_id = $user_id;

        $consent->save();

    }
}
