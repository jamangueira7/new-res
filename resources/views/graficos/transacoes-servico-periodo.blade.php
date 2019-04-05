<!-- #################### Transações x Serviço x Período - VISUAL #####################-->
<div class="box box-primary collapsed-box">
    <div class="box-header with-border">
        <h3 class="box-title">Transações x Serviço x Período</h3>

        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
            </button>
        </div>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        <!-- Transacoes x Servico x  Periodo -->
        <div class="col-md-12">
            <div class="box-body" style="">
                <canvas id="TransServicoPeriodo" style="height: 229px; width: 458px;" width="458" height="229"></canvas>
            </div>
        </div>
    </div>
    <!-- /.box-body -->
    <div class="box-footer text-center">

    </div>
    <!-- /.box-footer -->
</div>
<!-- ####################FIM Transações x Serviço x Período - VISUAL #####################-->
    <?php
    $results = DB::connection('oracle')
        ->select("SELECT
TO_CHAR(dt_log, 'dd-mm-yyyy') AS DT_log,
COUNT((CASE WHEN NM_SERVICO = '00720_envioDadosLaboratorio' THEN 1 ELSE NULL END)) AS s_00720,
COUNT((CASE WHEN NM_SERVICO = '00750_envioDadosLaboratorio' THEN 1 ELSE NULL END)) AS s_00750,
COUNT((CASE WHEN NM_SERVICO = '00770_recebeDadosDemograficos' THEN 1 ELSE NULL END)) AS s_00770,
COUNT((CASE WHEN NM_SERVICO = '00780_recebeDadosClinicos' THEN 1 ELSE NULL END)) AS s_00780,
COUNT((CASE WHEN NM_SERVICO = '00800_envioDadosLibero' THEN 1 ELSE NULL END)) AS s_00800
FROM
vw_log_transacao_res_xml
WHERE {$where}
GROUP BY TO_CHAR(dt_log, 'dd-mm-yyyy')
ORDER BY substr(DT_LOG,4,2) asc, substr(DT_LOG,0,2) asc");


    $Slabels = '';
    $S_720 = '';
    $S_750 = '';
    $S_770 = '';
    $S_780 = '';
    $S_800 = '';

    foreach($results as $result) {
        if (!empty($Slabels)) {
            $Slabels .= ',';
            $S_720 .= ',';
            $S_750 .= ',';
            $S_770 .= ',';
            $S_780 .= ',';
            $S_800 .= ',';
        }
        $Slabels .= '"'.$result->dt_log.'"';
        $S_720 .= $result->s_00720;
        $S_750 .= $result->s_00750;
        $S_770 .= $result->s_00770;
        $S_780 .= $result->s_00780;
        $S_800 .= $result->s_00800;
    }
    ?>
    <script>
        $(function () {
            var areaChartData = {
                labels  : [<?= $Slabels; ?>],
                datasets: [
                    {
                        label               : '720',
                        borderColor: '{{getColor(0)}}',
                        data                : [<?= $S_720; ?>]
                    },
                    {
                        label               : '750',
                        borderColor: '{{getColor(1)}}',
                        data                : [<?= $S_750; ?>]
                    },
                    {
                        label               : '770',
                        borderColor: '{{getColor(3)}}',
                        data                : [<?= $S_770; ?>]
                    },
                    {
                        label               : '780',
                        borderColor: '{{getColor(8)}}',
                        data                : [<?= $S_780; ?>]
                    },
                    {
                        label               : '800',
                        borderColor: '{{getColor(17)}}',
                        data                : [<?= $S_800; ?>]
                    }
                ]
            }

            var myChart2 =new Chart($('#TransServicoPeriodo'), {
                type: 'line',
                data: areaChartData,
                options: {
                    title: {
                        display: true,
                        text: 'Transações x Serviço x Periodo'
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
            document.getElementById("TransServicoPeriodo").onclick = function (evt) {
                var activePoints = myChart2.getElementsAtEventForMode(evt, 'point', myChart2.options);
                var firstPoint = activePoints[0];
                var date = myChart2.data.labels[firstPoint._index];
                var status = myChart2.data.datasets[firstPoint._datasetIndex].label;
                var value = myChart2.data.datasets[firstPoint._datasetIndex].data[firstPoint._index];
                //alert(label + ": " + value);

                $.ajax({
                    type: 'POST',
                    url: "_app/Helpers/ModalDetail.class.php",
                    dataType:'JSON',
                    data: { "grafic": "TransServicoPeriodo","date":date, "status": status },
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

								<p>Serviço</p>
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
