
    <div class="box-header with-border">
        <h3 class="box-title">Transações X Serviço X Status</h3>

        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
        </div>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        <div class="col-md-6">
            <div class="box-body" style="">
                <canvas id="itwvTypeService" style="height: 229px; width: 458px;" width="458" height="229"></canvas>
            </div>
        </div>

        <div class="col-md-6">
            <div class="box-body" style="">
                <canvas id="itwvErroAndSuccess" style="height: 229px; width: 458px;" width="458" height="229"></canvas>
            </div>
        </div>
        <div class="col-md-12">
            <div class="box-body" style="">
                <canvas id="itwvTransBySingular" style="height: 229px; width: 458px;" width="458" height="229"></canvas>
            </div>
        </div>
    </div>
    <!-- /.box-body -->
    <div class="box-footer text-center">

    </div>
    <!-- /.box-footer -->
    <?php
/*    $results = DB::connection('oracle')
        ->select("SELECT
                    TO_CHAR(dt_log, 'dd-mm-yyyy') AS DT_log,
                    COUNT ((CASE WHEN DS_STATUS = 'ERRO' THEN 1 ELSE NULL END)) AS ERRO,
                    COUNT ((CASE WHEN DS_STATUS = 'SUCESSO' THEN 1 ELSE NULL END)) AS SUCESSO
                    FROM
                    vw_log_transacao_res_xml
        where {$where} GROUP BY TO_CHAR(dt_log, 'dd-mm-yyyy') ORDER BY substr(DT_LOG,4,2) asc, substr(DT_LOG,0,2) asc");

    $val = '';
    $succ = '';
    $error = '';
    foreach($results as $result) {

        if (!empty($val)) {
            $val .= ',';
        }
        $val .= '"'.$result->dt_log .'"';
        $succ += $result->sucesso;
        $error += $result->erro;
    }
    */?>
