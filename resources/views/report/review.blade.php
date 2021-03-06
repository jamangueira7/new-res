@extends("templates.master")


@section('css-view')

@stop

@section('conteudo-view')
    <section class="content container-fluid">
        <?php
        $bet_ini = (!empty(session('success')['bet_ini'])) ? session('success')['bet_ini'] : '01/'.date('m/Y', strtotime('-0 months', strtotime(date('Y-m-d'))));
        $bet_fim = (!empty(session('success')['bet_fim'])) ? session('success')['bet_fim'] : date('t/m/Y', strtotime('-0 months', strtotime(date('Y-m-d'))));
        $contSelect = 0;
        $wCodUniFull = '';
        $wCodUni = '';
        ?>
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Filtro relatório:</h3>
            </div>

            {!! Form::open(['route'=> 'report.review-list','target' => '_blank','method' => 'post', 'class' => 'form-padrao']) !!}
                <div class="box-body">
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
                    <div class="form-group">
                        <label>Código do beneficiário:</label>
                        <input name="codigo_beneficiario" type="text" class="form-control" />
                    </div>
                    <div class="form-group">
                        <label>Nome do beneficiário:</label>
                        <input name="nome_beneficiario" type="text" class="form-control" />
                    </div>
                    <div class="form-group">
                        <label>Número sequência:</label>
                        <input name="numero_sequencia" type="text" class="form-control" />
                    </div>
                    <div class="form-group">
                        <label>Data Inicial:</label>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input name="data_inicial" type="text" value='<?= date('01/m/Y'); ?>' class="form-control pull-right datepi date">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Data Final:</label>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input name="data_final" type="text" value='<?= date('t/m/Y'); ?>' class="form-control pull-right datepi date">
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary" name="">Gerar relátorio</button>
                </div>
            {!! Form::close() !!}
        </div>
    </section>

@stop

@section('js-view')
   <script>


   </script>
@stop
