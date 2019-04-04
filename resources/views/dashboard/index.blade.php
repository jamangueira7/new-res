@extends("templates.master")


@section('css-view')

@stop

@section('conteudo-view')
    <section class="content-header">
        <h1>
            <span class="upercase">DASHBOARD</span>
        </h1>
    </section>
    <section class="content">
        <?php
         $bet_ini = (isset($_POST) && !empty($_POST['bet_ini'])) ? $_POST['bet_ini'] : '01/'.date('m/Y', strtotime('-0 months', strtotime(date('Y-m-d'))));
         $bet_fim = (isset($_POST) && !empty($_POST['bet_fim'])) ? $_POST['bet_fim'] : date('t/m/Y', strtotime('-0 months', strtotime(date('Y-m-d'))));
         $contSelect = 0;
        $wCodUniFull = '';
        $wCodUni = '';
         ?>
        <div class="box box-primary">
            <div class="box-body">

                <form class="" action="#" method="post">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Periodo</label>
                            <div class="row">
                                <div class="col-xs-6">
                                    <input placeholder="De" class="form-control datepi date" name="bet_ini" type="text" value="{{$bet_ini}}" required/>
                                </div>
                                <div class="col-xs-6">
                                    <input placeholder="Ate" class="form-control datepi date" name="bet_fim" type="text" value="{{$bet_fim}}" required/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Unimed:</label>
                            <select name="codigo_unimed[]" multiple class='form-control select2 j_codigo_unimed' style='width:100%;' required>
                               @if(!empty($unimeds))
                                   @foreach($unimeds as $unimed)
                                        <option selected value='{{$unimed->id_unimed}}'>{{$unimed->ds_unimed}}</option>
                                        <?php
                                        ((empty($wCodUniFull)) ? $wCodUniFull .= "'".$unimed->id_unimed."'" : $wCodUniFull .= ', '."'".$unimed->id_unimed."'");
                                        ((empty($wCodUni)) ? $wCodUni .= "'".substr($unimed->id_unimed, 0, 4)."'" : $wCodUni .= ', '."'".substr($unimed->id_unimed, 0, 4)."'");
                                        $contSelect ++;?>
                                   @endforeach
                                @endif
                            </select>

                            <label style="cursor:pointer;"><input type="checkbox" class="j_select_all" {{(count($unimeds) == $contSelect) ? 'checked' : ''}} >&nbsp;Todas as opções</label>
                        </div>
                    </div>
                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary" class='' name="">Filtrar</button>
                    </div>
                </form>
            </div>
        </div>

            <?php
$NomeDosServicos = " AND NM_SERVICO IN ('00720_envioDadosLaboratorio', '00750_envioDadosLaboratorio', '00770_recebeDadosDemograficos', '00780_recebeDadosClinicos', '00800_envioDadosLibero') ";
$where = '';
$wherebt = '';
$wheCodUni = '';
if (!isset($_POST['bet_ini'])) {
	$betweenIni = date('Y-m', strtotime('-2 months', strtotime(date('Y-m-d')))).'-01';
	$betweenFim = date('Y-m-t', strtotime('-0 months', strtotime(date('Y-m-d'))));

	$where = "((DT_LOG BETWEEN TO_DATE('".$betweenIni."', 'yyyy-mm-dd') AND TO_DATE('".$betweenFim."', 'yyyy-mm-dd'))) AND (ID_UNIMED IN (".$wCodUni.")) {$NomeDosServicos}";
	$wherebt = "((DT_LOG BETWEEN TO_DATE('".$betweenIni."', 'yyyy-mm-dd') AND TO_DATE('".$betweenFim."', 'yyyy-mm-dd'))) {$NomeDosServicos}";
	$wheCodUni = 'WHERE RUS.CD_SISTEMA_ORIGEM IN ('.$wCodUniFull.')';
	// $wheCodUni = 'WHERE SUBSTR (RUS.CD_SISTEMA_ORIGEM, 0, 4) IN ('.$wCodUni.')';
}else {
	// PREPARA BETWEEN
	if ($_POST['bet_ini'] && $_POST['bet_fim']) {
		$wherebt = "((DT_LOG BETWEEN TO_DATE('".Check::DataSimple($_POST['bet_ini'])."', 'yyyy-mm-dd') AND TO_DATE('".Check::DataSimple($_POST['bet_fim'])."', 'yyyy-mm-dd'))) {$NomeDosServicos}";
	}

	// PREPARA CODIGO UNIMED
	if (!empty($_POST['codigo_unimed'])) {
		$wCodUnimed = " (ID_UNIMED IN (".$wCodUni."))";
		$wheCodUni = 'WHERE RUS.CD_SISTEMA_ORIGEM IN ('.$wCodUniFull.')';
		// $wheCodUni = 'WHERE SUBSTR (RUS.CD_SISTEMA_ORIGEM, 0, 4) IN ('.$aux.')';
	}

	$where .= ((empty($where)) ? ((empty($wherebt)) ? '' : 'WHERE '.$wherebt) : ((empty($wherebt)) ? '' : 'AND '.$wherebt));
	$where .= ((empty($where)) ? ((empty($wCodUnimed)) ? '' : 'WHERE '.$wCodUnimed) : ((empty($wCodUnimed)) ? '' : 'AND '.$wCodUnimed));
	$where .= ((empty($where)) ? "NM_SERVICO IN ('00720_envioDadosLaboratorio', '00750_envioDadosLaboratorio', '00770_recebeDadosDemograficos', '00780_recebeDadosClinicos', '00800_envioDadosLibero')" : $NomeDosServicos);

}
?>

        <div class="box box-primary">
            {{--GRAFICO - Transações X Serviço X Status--}}
            @include('graficos.transacoes-servico-status', ['where' => $where])
        </div>
        <div class="box box-primary collapsed-box">
            {{--GRAFICO - Transações x Status x Período--}}
            @include('graficos.transacoes-status-periodo', ['where' => $where])
        </div>


        <div class="box box-primary collapsed-box">
            {{--GRAFICO - Transações x Serviço x Período--}}
            @include('graficos.transacoes-servico-periodo', ['where' => $where])
        </div>



        <div class="box box-primary collapsed-box">
            {{--GRAFICO - Transações x Singular x Período--}}
            @include('graficos.transacoes-singular-periodo', ['where' => $where, 'whereCodUni' => $wheCodUni])
        </div>

        <div class="box box-primary collapsed-box">
            {{--GRAFICO - Transações x Status x Período x Serviço--}}
            @include('graficos.transacoes-status-periodo-servico', ['where' => $where])
        </div>

        <div class="box box-primary collapsed-box">
            {{--GRAFICO - Transações x Singular x Serviço--}}
            @include('graficos.transacoes-singular-servico', ['where' => $where])
        </div>
    </section>
@stop

@section('js-view')

@stop
