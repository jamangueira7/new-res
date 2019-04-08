@extends("templates.master")


@section('css-view')

@stop

@section('conteudo-view')
    <section class="content container-fluid">
        {!! Form::open(['route'=> 'user.store','method' => 'post', 'class' => '']) !!}
                <div class="box-body">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"></h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="box-body">
                                <div class="form-group">
                                    <label>*Nome </label>
                                    <input class="form-control"  name="name" type="text" value="" required />
                                </div>
                                <div class="form-group">
                                    <label>*E-mail </label>
                                    <input class="form-control" name="email" type="text" value="" required />
                                </div>
                                <div class="form-group">
                                    <label>*Senha </label>
                                    <input class="form-control" name="password" type="password" value="" required />
                                </div>
                                <div class="form-group">
                                    <label>*Confirmar senha </label>
                                    <input class="form-control" name="confirm" type="password" value="" required />
                                </div>
                                <div class="form-group">
                                    <label>*Permissão </label>
                                    <select class="form-control"  name="level">
                                        @foreach($levels as $level)
                                            <option value="{{$level->id}}" {{$level->id == 2 ? "selected" : ""}}>{{$level->description}}</option>';
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>CPF</label>
                                    <input class="form-control cpf"  name="cpf" type="text" value="" />
                                </div>
                                <div class="form-group">
                                    <label>Data de nascimento</label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input name="nascimento"  class="form-control pull-right datepi date" value='{{date('d/m/Y')}}' type="text" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Sexo</label>
                                    <select class="form-control"  name="sex">
                                        <option value="M" selected>Masculino</option>';
                                        <option value="F" >Feminino</option>';
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary">Salvar</button>
                    </div>
                </div>
        {!! Form::close() !!}
    </section>
@stop

@section('js-view')
   <script>


   </script>
@stop
