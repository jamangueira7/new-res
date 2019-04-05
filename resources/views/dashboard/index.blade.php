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
        $bet_ini = (!empty(session('success')['bet_ini'])) ? session('success')['bet_ini'] : '01/'.date('m/Y', strtotime('-0 months', strtotime(date('Y-m-d'))));
        $bet_fim = (!empty(session('success')['bet_fim'])) ? session('success')['bet_fim'] : date('t/m/Y', strtotime('-0 months', strtotime(date('Y-m-d'))));
        $contSelect = 0;
        $wCodUniFull = '';
        $wCodUni = '';
         ?>
        <div class="box box-primary">
            <div class="box-body">

                {!! Form::open(['route'=> 'search.dashboard','method' => 'post', 'class' => 'form-padrao']) !!}
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
                            <select name="codigo_unimed[]" id="codigo_unimed" multiple class='form-control select2 j_codigo_unimed' style='width:100%;' required>

                                @if(!empty($unimeds))
                                   @foreach($unimeds as $unimed)
                                        @if(!empty(session('success')['codigo_unimed']))
                                            @if(in_array($unimed->id_unimed, session('success')['codigo_unimed']))
                                                <option selected value='{{$unimed->id_unimed}}'>{{$unimed->ds_unimed}}</option>
                                                <?php
                                                    ((empty($wCodUniFull)) ? $wCodUniFull .= "'".$unimed->id_unimed."'" : $wCodUniFull .= ', '."'".$unimed->id_unimed."'");
                                                    ((empty($wCodUni)) ? $wCodUni .= "'".substr($unimed->id_unimed, 0, 4)."'" : $wCodUni .= ', '."'".substr($unimed->id_unimed, 0, 4)."'");
                                                    $contSelect ++;
                                                ?>
                                            @else
                                                <option value='{{$unimed->id_unimed}}'>{{$unimed->ds_unimed}}</option>
                                            @endif
                                        @else
                                            <option selected value='{{$unimed->id_unimed}}'>{{$unimed->ds_unimed}}</option>
                                            <?php
                                            ((empty($wCodUniFull)) ? $wCodUniFull .= "'".$unimed->id_unimed."'" : $wCodUniFull .= ', '."'".$unimed->id_unimed."'");
                                            ((empty($wCodUni)) ? $wCodUni .= "'".substr($unimed->id_unimed, 0, 4)."'" : $wCodUni .= ', '."'".substr($unimed->id_unimed, 0, 4)."'");
                                            $contSelect ++;
                                            ?>
                                        @endif
                                   @endforeach
                                @endif
                            </select>

                            <label style="cursor:pointer;"><input type="checkbox" class="j_select_all" {{(count($unimeds) == $contSelect) ? 'checked' : ''}} >&nbsp;Todas as opções</label>
                        </div>
                    </div>
                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary" id="ajaxsubmit">Filtrar</button>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
            <?php
                $NomeDosServicos = " AND NM_SERVICO IN ('00720_envioDadosLaboratorio', '00750_envioDadosLaboratorio', '00770_recebeDadosDemograficos', '00780_recebeDadosClinicos', '00800_envioDadosLibero') ";
                $where = '';
                $wherebt = '';
                $wheCodUni = '';
                if (empty(session('success')['bet_ini'])) {
                    $betweenIni = date('Y-m', strtotime('-2 months', strtotime(date('Y-m-d')))).'-01';
                    $betweenFim = date('Y-m-t', strtotime('-0 months', strtotime(date('Y-m-d'))));

                    $where = "((DT_LOG BETWEEN TO_DATE('".$betweenIni."', 'yyyy-mm-dd') AND TO_DATE('".$betweenFim."', 'yyyy-mm-dd'))) AND (ID_UNIMED IN (".$wCodUni.")) {$NomeDosServicos}";
                    $wherebt = "((DT_LOG BETWEEN TO_DATE('".$betweenIni."', 'yyyy-mm-dd') AND TO_DATE('".$betweenFim."', 'yyyy-mm-dd'))) {$NomeDosServicos}";
                    $wheCodUni = 'WHERE RUS.CD_SISTEMA_ORIGEM IN ('.$wCodUniFull.')';
                    // $wheCodUni = 'WHERE SUBSTR (RUS.CD_SISTEMA_ORIGEM, 0, 4) IN ('.$wCodUni.')';
                }else {
                    // PREPARA BETWEEN
                    if (!empty(session('success')['bet_ini']) && !empty(session('success')['bet_ini'])) {
                        $wherebt = "((DT_LOG BETWEEN TO_DATE('".dataSimple(session('success')['bet_ini'])."', 'yyyy-mm-dd') AND TO_DATE('".dataSimple(session('success')['bet_fim'])."', 'yyyy-mm-dd'))) {$NomeDosServicos}";
                    }

                    // PREPARA CODIGO UNIMED
                    if (!empty(session('success')['codigo_unimed'])) {
                        $wCodUnimed = " (ID_UNIMED IN (".$wCodUni."))";
                        $wheCodUni = 'WHERE RUS.CD_SISTEMA_ORIGEM IN ('.$wCodUniFull.')';
                        // $wheCodUni = 'WHERE SUBSTR (RUS.CD_SISTEMA_ORIGEM, 0, 4) IN ('.$aux.')';
                    }

                    $where .= ((empty($where)) ? ((empty($wherebt)) ? '' : $wherebt) : ((empty($wherebt)) ? '' : 'AND '.$wherebt));
                    $where .= ((empty($where)) ? ((empty($wCodUnimed)) ? '' : $wCodUnimed) : ((empty($wCodUnimed)) ? '' : 'AND '.$wCodUnimed));
                    $where .= ((empty($where)) ? "NM_SERVICO IN ('00720_envioDadosLaboratorio', '00750_envioDadosLaboratorio', '00770_recebeDadosDemograficos', '00780_recebeDadosClinicos', '00800_envioDadosLibero')" : $NomeDosServicos);

                }
                ?>


            {{--GRAFICO - Transações X Serviço X Status--}}
            @include('graficos.transacoes-servico-status', ['where' => $where])

            {{--GRAFICO - Transações x Singular --}}
            @include('graficos.transacoes-singular', ['where' => $where, 'whereCodUni' => $wheCodUni, 'wherebt' => $wherebt])

            {{--GRAFICO - Transações x Status x Período--}}
            @include('graficos.transacoes-status-periodo', ['where' => $where])

            {{--GRAFICO - Transações x Serviço x Período--}}
            @include('graficos.transacoes-servico-periodo', ['where' => $where])

            {{--GRAFICO - Transações x Singular x Período--}}
            @include('graficos.transacoes-singular-periodo', ['where' => $where, 'whereCodUni' => $wheCodUni])


            {{--GRAFICO - Transações x Status x Serviço--}}
            @include('graficos.transacoes-status-servico', ['where' => $where])


            {{--GRAFICO - Transações x Singular x Serviço--}}
            @include('graficos.transacoes-singular-servico', ['where' => $where])

    </section>
@stop

@section('js-view')
   <script>


   </script>
@stop
