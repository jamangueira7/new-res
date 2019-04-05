<!-- #################### Transações x Singular x Período - VISUAL #####################-->
<div class="box box-primary collapsed-box">
    <div class="box-header with-border">
        <h3 class="box-title">Transações x Singular x  Período</h3>

        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
            </button>
        </div>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        <!-- Transacoes x Singular x  Periodo -->
        <div class="col-md-12">
            <div class="box-body" style="">
                <canvas id="TransSingularPeriodo" style="height: 229px; width: 458px;" width="458" height="229"></canvas>
            </div>
        </div>
    </div>
    <!-- /.box-body -->
    <div class="box-footer text-center">

    </div>
    <!-- /.box-footer -->
</div>
<!-- ####################FIM Transações x Singular x Período - VISUAL #####################-->
<?php

$results = DB::connection('oracle')
    ->select("SELECT
TO_CHAR(dt_log, 'dd-mm-yyyy') AS DT_log,
ID_UNIMED, DS_UNIMED, count(*) as cont
FROM
vw_log_transacao_res_xml
WHERE {$where}
GROUP BY TO_CHAR(dt_log, 'dd-mm-yyyy'), ID_UNIMED, DS_UNIMED
ORDER BY substr(DT_LOG,4,2) asc, substr(DT_LOG,0,2) asc");

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

            if(empty($controlaLabels[$result->dt_log]) || $result->dt_log != $controlaLabels[$result->dt_log] ){
                if (!empty($labels)) {
                    $labels .= ',';
                }
                $labels .= '"'.$result->dt_log.'"';
                $controlaLabels[$result->dt_log] = $result->dt_log;
            }
            $singularColuna[$key][$result->dt_log] = $result->cont;
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
                    borderColor: '{{getColor($i)}}',
                    data                : [<?= implode(",", $value); ?>]
                },
                <?php
                $i++;
                }
                ?>
            ]
        }

        var myChart3 = new Chart($('#TransSingularPeriodo'), {
            type: 'line',
            data: areaChartData,
            options: {
                title: {
                    display: true,
                    text: 'Transações x Singular x Periodo'
                },
                click: function(e){
                    alert(  e.dataSeries.type+ " x:" + e.dataPoint.x + ", y: "+ e.dataPoint.y);
                },
            }
        });

        /*
        * #######################################
        * Mostrando detalhes do grafico
        * #######################################
        */
        document.getElementById("TransSingularPeriodo").onclick = function (evt) {
            var activePoints = myChart3.getElementsAtEventForMode(evt, 'point', myChart3.options);
            var firstPoint = activePoints[0];
            var date = myChart3.data.labels[firstPoint._index];
            var status = myChart3.data.datasets[firstPoint._datasetIndex].label;
            var value = myChart3.data.datasets[firstPoint._datasetIndex].data[firstPoint._index];
            //alert(label + ": " + value);

            $.ajax({
                type: 'POST',
                url: "_app/Helpers/ModalDetail.class.php",
                dataType:'JSON',
                data: { "grafic": "TransSingularPeriodo","date":date, "status": status },
                error: function(msg) {
                    //console.log(msg);
                    $('#modal-body').html(msg.responseText);
                    // $('#modal-titel').html("Detalhes:</br> Status - " + status + "</br> Data - " + date + "</br> Quantidade de registros - " + value);
                    $('#modal-titel').html(`
				<div class="row">
					<div class="col-lg-3 col-xs-6" style="width: 100%; float: left;">
						<div class="small-box `+((status == 'Sucesso') ? `bg-aqua` : `bg-red` )+`">
							<div class="inner">
								<h3>`+status+`</h3>

								<p>Singular</p>
							</div>
							<div class="icon">
								<i class="fa fa-bar-chart"></i>
							</div>
						</div>
					</div>
					<div class="col-lg-3 col-xs-6" style="width: 49.7%; float: left; margin-right: 0.6%;">
						<div class="small-box bg-green">
							<div class="inner">
								<h3>`+date+`</h3>

								<p>Data</p>
							</div>
							<div class="icon">
								<i class="fa fa-calendar"></i>
							</div>
						</div>
					</div>
					<div class="col-lg-3 col-xs-6" style="width: 49.7%; float: left;">
						<div class="small-box bg-yellow">
							<div class="inner">
								<h3>`+value+`</h3>

								<p>Quantidade de registros</p>
							</div>
							<div class="icon">
								<i class="fa fa-cubes"></i>
							</div>
						</div>
					</div>
				</div>`);
                },
                success: function (msg){
                    $('#modal-body').html("<h3>Ocorreu um erro inesperado, por favor tente de novo. Caso o erro persista informe a nosso administradores.</h3>");
                }
            });

            $("#modal_detail").modal();
        }
    });
</script>

