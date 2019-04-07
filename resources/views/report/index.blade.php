@extends("templates.master")


@section('css-view')

@stop

@section('conteudo-view')
    <section class="content container-fluid">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Filtro relatório:</h3>
            </div>

            {!! Form::open(['route'=> 'report.access-list','target' => '_blank','method' => 'post', 'class' => 'form-padrao']) !!}
                <div class="box-body">
                    <div class="form-group">
                        <label>Usuários:</label>
                        <select class="form-control" name="codigo_usuario">
                            <option value='0'>Todos</option>
                                @foreach ($users as $user)
                                    extract($value);
                                   <option value="{{$user->id}}">{{mb_convert_case($user->name, MB_CASE_UPPER)}}</option>
                                @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Data Inicial:</label>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input name="data_inicial" type="text" value='{{date('01/m/Y')}}' class="form-control pull-right datepi date">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Data Final:</label>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input name="data_final" type="text" value='<{{ date('t/m/Y')}}' class="form-control pull-right datepi date">
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary" name="">Gerar relatório</button>
                </div>
            {!! Form::close() !!}
        </div>
    </section>
@stop

@section('js-view')
   <script>


   </script>
@stop
