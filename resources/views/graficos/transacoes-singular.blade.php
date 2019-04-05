<!-- ####################Transações X Singular - VISUAL #####################-->
<div class="box box-primary collapsed-box">
    <div class="box-header with-border">
        <h3 class="box-title">Transações X Singular</h3>

        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
            </button>
        </div>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
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
</div>
<!-- ####################FIM Transações X Singular - VISUAL #####################-->
<?php

$results = DB::connection('oracle')
    ->select("SELECT
SUBSTR (RUS.CD_SISTEMA_ORIGEM, 0, 4) AS ID_UNIMED,
RUS.NM_UNIDADE_SAUDE,
(
	SELECT
	COUNT (LTR.ID_UNIMED)
	FROM
	vw_log_transacao_res_xml LTR
	WHERE
	LTR.ID_UNIMED = SUBSTR (RUS.CD_SISTEMA_ORIGEM, 0, 4) AND {$wherebt}
) AS TRANSACOES
FROM
RES_UNIDADE_SAUDE RUS
{$wheCodUni}
ORDER BY NM_UNIDADE_SAUDE");

$nome_unimed = array();
$val_nome_unimed = '';
$val_trans_unimed = '';
$val_color = '';
$i = 0;
foreach($results as $result) {
    $nome_unimed[$result->id_unimed][$result->nm_unidade_saude] = $result->transacoes;
    if (!empty($val_nome_unimed)) {
        $val_nome_unimed .= ',';
        $val_trans_unimed .= ',';
        $val_color .= ',';
    }
    $val_nome_unimed .= '"'.$result->nm_unidade_saude.'"';
    $val_trans_unimed .= '"'.$result->transacoes.'"';
    $val_color .= '"'.getColor($i).'"';
    $i++;
}

?>
<script>

        // #itwvTransBySingular
        options = {
            scales: {
                xAxes: [{
                    ticks: {
                        beginAtZero: true,
                        userCallback: function(label, index, labels) {
                            // when the floored value is the same as the value we have a whole number
                            if (Math.floor(label) === label) {
                                return label;
                            }

                        }
                    }
                }],
            },
            title: {
                display: true,
                text: 'Por Unimed'
            },
            legend: {
                display: false
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
                                ctx.fillText(data, bar._model.x + 10, bar._model.y + 7 );
                            }

                        });
                    });
                }
            },
            tooltipTemplate: "<%= value %>",
            showTooltips: true,
            onAnimationComplete: function() {
                this.showTooltip(this.datasets[0].points, true);
            },
            tooltipEvents: []
        }

        new Chart($('#itwvTransBySingular'), {
            type: 'horizontalBar',
            data: {
                labels: [<?= $val_nome_unimed; ?>],
                datasets: [{
                    label: "Transações",
                    backgroundColor: [<?= $val_color;?>],
                    data: [<?= $val_trans_unimed;?>]
                }]
            },
            options: options
        });

</script>

