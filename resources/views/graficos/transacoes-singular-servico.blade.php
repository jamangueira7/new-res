<!-- #################### Transações x Singular x Serviço - VISUAL #####################-->
<div class="box box-primary collapsed-box">
    <div class="box-header with-border">
        <h3 class="box-title">Transações x Singular x Serviço</h3>

        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
            </button>
        </div>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        <!-- Transacoes x Singular x  Servico -->
        <div class="col-md-12">
            <div class="box-body" style="">
                <canvas id="TransSingularServico" style="height: 229px; width: 458px;" width="458" height="229"></canvas>
            </div>
        </div>
    </div>
    <!-- /.box-body -->
    <div class="box-footer text-center">

    </div>
    <!-- /.box-footer -->
</div>
<!-- ####################FIM Transações x Singular x Serviço - VISUAL #####################-->

<?php

$results = DB::connection('oracle')
    ->select("SELECT
NM_SERVICO,
ID_UNIMED, DS_UNIMED, count(*) as cont
FROM
vw_log_transacao_res_xml
WHERE {$where}
GROUP BY NM_SERVICO, ID_UNIMED, DS_UNIMED
ORDER BY NM_SERVICO asc");

$results2 = DB::connection('oracle')
    ->select("SELECT SUBSTR (RUS.CD_SISTEMA_ORIGEM, 0, 4) AS ID_UNIMED,
       RUS.NM_UNIDADE_SAUDE
FROM RES_UNIDADE_SAUDE RUS
{$wheCodUni}
ORDER BY RUS.NM_UNIDADE_SAUDE");

$singular = array();
foreach($results2 as $result) {
    $singular[$result->nm_unidade_saude] = $result->id_unimed;
}

$labels = '';
$singularColuna = array();
$controlaLabels = array();

foreach ($singular as $key=>$value){
    foreach($results as $result) {

        if($key == $result->ds_unimed){

            if(empty($controlaLabels[$result->nm_servico]) || $result->nm_servico != $controlaLabels[$result->nm_servico] ){
                if (!empty($labels)) {
                    $labels .= ',';
                }
                $labels .= '"'.$result->nm_servico.'"';
                $controlaLabels[$result->nm_servico] = $result->nm_servico;
            }
            $singularColuna[$key][$result->nm_servico] = $result->cont;
        }else{
            $singularColuna[$key][0] = 0;
        }
    }
}
?>
<script>
    $(function () {
        var areaChartData = {
            labels  : [<?= $labels; ?>],
            datasets: [
                    <?php
                    $i = 0;
                    foreach ($singularColuna as $key => $value) {
                        if(count($singularColuna[$key]) > 1){
                            unset($singularColuna[$key][0]);
                        }
                    ?>
                {
                    label               : '<?= str_replace('_', ' ', $key) ?>',
                    backgroundColor: '<?= getColor($i); ?>',
                    data                : [<?= implode(",", $value); ?>]
                },
                <?php
                $i++;
                }
                ?>
            ]
        }

        new Chart($('#TransSingularServico'), {
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
                                if(data != 0){
                                    ctx.fillText(data, bar._model.x, bar._model.y - 5 );
                                }

                            });
                        });
                    }
                },
            }
        });
    });
</script>
