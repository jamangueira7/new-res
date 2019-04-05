<!-- #################### Transações x Status x Período - VISUAL #####################-->
<div class="box box-primary collapsed-box">
    <div class="box-header with-border">
        <h3 class="box-title">Transações x Status x  Período</h3>

        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
            </button>
        </div>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        <!-- Transacoes x Status x  Período -->
        <div class="col-md-12">
            <div class="box-body" style="">
                <canvas id="TransStatusPeriodo" style="height: 229px; width: 458px;" width="458" height="229"></canvas>
            </div>
        </div>
    </div>
    <!-- /.box-body -->
    <div class="box-footer text-center">

    </div>
    <!-- /.box-footer -->
</div>
<!-- ####################FIM Transações x Status x Período - VISUAL #####################-->
    <?php
    $results = DB::connection('oracle')
    ->select("SELECT
TO_CHAR(dt_log, 'dd-mm-yyyy') AS DT_log,
COUNT ((CASE WHEN DS_STATUS = 'ERRO' THEN 1 ELSE NULL END)) AS ERRO,
COUNT ((CASE WHEN DS_STATUS = 'SUCESSO' THEN 1 ELSE NULL END)) AS SUCESSO
FROM
vw_log_transacao_res_xml
        where {$where}
        GROUP BY TO_CHAR(dt_log, 'dd-mm-yyyy')
        ORDER BY substr(DT_LOG,4,2) asc, substr(DT_LOG,0,2) asc");

    $labels = '';
    $success = '';
    $erro = '';
    foreach($results as $result) {

        if (!empty($labels)) {
            $labels .= ',';
            $success .= ',';
            $erro .= ',';
        }
        $labels .= '"'.$result->dt_log .'"';
        $success .= $result->sucesso;
        $erro .= $result->erro;
    }
    ?>
    <script>
        $(function () {
            var areaChartData = {
                labels  : [<?= $labels; ?>],
                datasets: [
                    {
                        label               : 'Sucesso',
                        borderColor: '#3b8bba',
                        data                : [<?= $success; ?>]
                    },
                    {
                        label               : 'Erro',
                        borderColor: 'red',
                        data                : [<?= $erro; ?>]
                    }
                ]
            }

            var myChart = new Chart($('#TransStatusPeriodo'), {
                type: 'line',
                data: areaChartData,
                options: {
                    title: {
                        display: true,
                        text: 'Transações x Status x Periodo'
                    },
                    events: ['click']
                }
            });

            /*
            * #######################################
            * Mostrando detalhes do grafico
            * #######################################
            */
            document.getElementById("TransStatusPeriodo").onclick = function (evt) {
                var activePoints = myChart.getElementsAtEventForMode(evt, 'point', myChart.options);
                var firstPoint = activePoints[0];
                var date = myChart.data.labels[firstPoint._index];
                var status = myChart.data.datasets[firstPoint._datasetIndex].label;
                var value = myChart.data.datasets[firstPoint._datasetIndex].data[firstPoint._index];
                //alert(label + ": " + value);

                $.ajax({
                    type: 'POST',
                    url: "_app/Helpers/ModalDetail.class.php",
                    dataType:'JSON',
                    data: { "date":date, "status": status },
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

								<p>Status</p>
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
            <!-- /.box-footer -->
    {{--</div>--}}

