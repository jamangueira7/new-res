<!-- ####################Transações X Serviço X Status - VISUAL #####################-->
<div class="box box-primary ">
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
    </div>
    <!-- /.box-body -->
    <div class="box-footer text-center">

    </div>
    <!-- /.box-footer -->
</div>
<!-- ####################FIM Transações X Serviço X Status - VISUAL #####################-->
    <?php

    $results2 = DB::connection('oracle')
        ->select("SELECT
TO_CHAR(dt_log, 'dd-mm-yyyy') AS DT_log,
COUNT((CASE WHEN NM_SERVICO = '00720_envioDadosLaboratorio' THEN 1 ELSE NULL END)) AS s_00720,
COUNT((CASE WHEN NM_SERVICO = '00750_envioDadosLaboratorio' THEN 1 ELSE NULL END)) AS s_00750,
COUNT((CASE WHEN NM_SERVICO = '00770_recebeDadosDemograficos' THEN 1 ELSE NULL END)) AS s_00770,
COUNT((CASE WHEN NM_SERVICO = '00780_recebeDadosClinicos' THEN 1 ELSE NULL END)) AS s_00780,
COUNT((CASE WHEN NM_SERVICO = '00800_envioDadosLibero' THEN 1 ELSE NULL END)) AS s_00800
FROM
vw_log_transacao_res_xml
        where {$where}
        GROUP BY TO_CHAR(dt_log, 'dd-mm-yyyy')
        ORDER BY substr(DT_LOG,4,2) asc, substr(DT_LOG,0,2) asc");

    $labels = '';
    $_720 = 0;
    $_750 = 0;
    $_770 = 0;
    $_780 = 0;
    $_800 = 0;

    foreach($results2 as $result) {

        $labels .= '"'.$result->dt_log.'"';
        $_720 += $result->s_00720;
        $_750 += $result->s_00750;
        $_770 += $result->s_00770;
        $_780 += $result->s_00780;
        $_800 += $result->s_00800;
    }

    $sum = $_720 + $_750 + $_770 + $_780 + $_800;

    $percent_720 = ($sum > 0) ? number_format((100*$_720)/$sum, 3) : 0;
    $percent_750 = ($sum > 0) ? number_format((100*$_750)/$sum, 3) : 0;
    $percent_770 = ($sum > 0) ? number_format((100*$_770)/$sum, 3) : 0;
    $percent_780 = ($sum > 0) ? number_format((100*$_780)/$sum, 3) : 0;
    $percent_800 = ($sum > 0) ? number_format((100*$_800)/$sum, 3) : 0;

   $results = DB::connection('oracle')
        ->select("SELECT
                    TO_CHAR(dt_log, 'dd-mm-yyyy') AS DT_log,
                    COUNT ((CASE WHEN DS_STATUS = 'ERRO' THEN 1 ELSE NULL END)) AS ERRO,
                    COUNT ((CASE WHEN DS_STATUS = 'SUCESSO' THEN 1 ELSE NULL END)) AS SUCESSO
                    FROM
                    vw_log_transacao_res_xml
        where {$where} GROUP BY TO_CHAR(dt_log, 'dd-mm-yyyy') ORDER BY substr(DT_LOG,4,2) asc, substr(DT_LOG,0,2) asc");

    $val = '';
    $succ = 0;
    $error = 0;
    foreach($results as $result) {

        if (!empty($val)) {
            $val .= ',';
        }
        $val .= '"'.$result->dt_log .'"';
        $succ += $result->sucesso;
        $error += $result->erro;
    }
    ?>

<script>

        new Chart($('#itwvTypeService'), {
            type: 'doughnut',
            data: {
                labels: ["720 (<?= $percent_720;?>%)", "750 (<?= $percent_750;?>%)", "770 (<?= $percent_770;?>%)", "780 (<?= $percent_780;?>%)", "800 (<?= $percent_800;?>%)"],
                datasets: [{
                    label: "Tipo de serviços",
                    backgroundColor: ["#3e95cd", "#8e5ea2","#3cba9f","#e8c3b9","#c45850"],
                    data: [<?= $_720;?>, <?= $_750;?>, <?= $_770;?>, <?= $_780;?>, <?= $_800;?>]
                }]
            },
            options: {
                title: {
                    display: true,
                    text: 'Transações por tipo de serviço'
                }
                // showAllTooltips: true
            }
        });

        <?php
        $sum = $succ + $error;

        $percent_sucesso = ($sum > 0) ? number_format((100*$succ)/$sum, 3) : 0;
        $percent_erro = ($sum > 0) ? number_format((100*$error)/$sum, 3) : 0;
        ?>
        new Chart($('#itwvErroAndSuccess'), {
            type: 'doughnut',
            data: {
                labels: ["SUCESSO (<?= $percent_sucesso;?>%)", "ERRO (<?= $percent_erro;?>%)"],
                datasets: [{
                    label: "Tipo de serviços",
                    backgroundColor: ["#3e95cd", "#8e5ea2","#3cba9f","#e8c3b9","#c45850"],
                    data: [<?= $succ;?>, <?= $error;?>]
                }]
            },
            options: {
                title: {
                    display: true,
                    text: 'Sucesso e Erro'
                }
            }
        });

</script>
