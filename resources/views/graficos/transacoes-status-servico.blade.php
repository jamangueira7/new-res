<!-- #################### Transações x Status x Serviço - VISUAL #####################-->
<div class="box box-primary collapsed-box">
    <div class="box-header with-border">
        <h3 class="box-title">Transações x Status x Serviço</h3>

        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
            </button>
        </div>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        <!-- Transacoes x Status x  Periodo x Servico -->
        <div class="col-md-12">
            <div class="box-body" style="">
                <canvas id="TransStatusServico" style="height: 229px; width: 458px;" width="458" height="229"></canvas>
            </div>
        </div>
    </div>
    <!-- /.box-body -->
    <div class="box-footer text-center">

    </div>
</div>
<!-- ####################FIM Transações x Status x Serviço - VISUAL #####################-->
<?php
$results = DB::connection('oracle')
    ->select("SELECT
       COUNT((CASE WHEN NM_SERVICO = '00720_envioDadosLaboratorio' AND DS_STATUS = 'ERRO' THEN 1 ELSE NULL END)) AS ERRO_00720,
       COUNT((CASE WHEN NM_SERVICO = '00720_envioDadosLaboratorio' AND DS_STATUS = 'SUCESSO' THEN 1 ELSE NULL END)) AS SUCESSO_00720,
       COUNT((CASE WHEN NM_SERVICO = '00750_envioDadosLaboratorio' AND DS_STATUS = 'ERRO' THEN 1 ELSE NULL END)) AS ERRO_00750,
       COUNT((CASE WHEN NM_SERVICO = '00750_envioDadosLaboratorio' AND DS_STATUS = 'SUCESSO' THEN 1 ELSE NULL END)) AS SUCESSO_00750,
       COUNT((CASE WHEN NM_SERVICO = '00770_recebeDadosDemograficos' AND DS_STATUS = 'ERRO' THEN 1 ELSE NULL END)) AS ERRO_00770,
       COUNT((CASE WHEN NM_SERVICO = '00770_recebeDadosDemograficos' AND DS_STATUS = 'SUCESSO' THEN 1 ELSE NULL END)) AS SUCESSO_00770,
       COUNT((CASE WHEN NM_SERVICO = '00780_recebeDadosClinicos' AND DS_STATUS = 'ERRO' THEN 1 ELSE NULL END)) AS ERRO_00780,
       COUNT((CASE WHEN NM_SERVICO = '00780_recebeDadosClinicos' AND DS_STATUS = 'SUCESSO' THEN 1 ELSE NULL END)) AS SUCESSO_00780,
       COUNT((CASE WHEN NM_SERVICO = '00800_envioDadosLibero' AND DS_STATUS = 'ERRO' THEN 1 ELSE NULL END)) AS ERRO_00800,
       COUNT((CASE WHEN NM_SERVICO = '00800_envioDadosLibero' AND DS_STATUS = 'SUCESSO' THEN 1 ELSE NULL END)) AS SUCESSO_00800
FROM vw_log_transacao_res_xml
WHERE {$where}");


$suc = '';
$err = '';

foreach($results as $result) {
    $err.= $result->erro_00720 . ",";
    $suc .= $result->sucesso_00720 . ",";

    $err.= $result->erro_00750 . ",";
    $suc .= $result->sucesso_00750 . ",";

    $err.= $result->erro_00770 . ",";
    $suc .= $result->sucesso_00770 . ",";

    $err.= $result->erro_00780 . ",";
    $suc .= $result->sucesso_00780 . ",";

    $err.= $result->erro_00800 ;
    $suc .= $result->sucesso_00800 ;
}
?>

<script>
    $(function () {
        var areaChartData = {
            labels  : ['720', '750','770', '780', '800'],
            datasets: [
                {
                    label               : 'Sucesso',
                    backgroundColor: '#3b8bba',
                    data                : [<?= $suc; ?>]
                },
                {
                    label               : 'Erro',
                    backgroundColor: 'red',
                    data                : [<?= $err; ?>]
                },
            ]
        }

        new Chart($('#TransStatusServico'), {
            type: 'bar',
            data: areaChartData,
            options: {
                title: {
                    display: true,
                    text: 'Transações x Serviço x Periodo'
                },
                animation: {
                    duration: 1,
                    onComplete: function () {
                        var chartInstance = this.chart,
                            ctx = chartInstance.ctx;
                        ctx.textAlign = 'center';
                        ctx.fillStyle = "rgba(0, 0, 0, 1)";
                        ctx.textBaseline = 'bottom';

                        this.data.datasets.forEach(function (dataset, i) {
                            var meta = chartInstance.controller.getDatasetMeta(i);
                            meta.data.forEach(function (bar, index) {
                                var data = dataset.data[index];
                                ctx.fillText(data, bar._model.x, bar._model.y - 5);

                            });
                        });
                    }
                }
            }

        });
    });
</script>
