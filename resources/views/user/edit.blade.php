@extends("templates.master")


@section('css-view')

@stop

@section('conteudo-view')
    <section class="content container-fluid">
        {!! Form::model($user,['route'=> ['user.update',$user->id],'method' => 'put', 'class' => '']) !!}

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
                                    <input class="form-control"  name="name" type="text" value="{{$user->name}}" required />
                                </div>
                                <div class="form-group">
                                    <label>*E-mail </label>
                                    <input class="form-control" disabled name="email" type="text" value="{{$user->email}}" required />
                                </div>
                                <div class="form-group">
                                    <label>CPF</label>
                                    <input class="form-control cpf"  name="cpf" type="text" value="{{$user->cpf}}" />
                                </div>
                                <div class="form-group">
                                    <label>Data de nascimento</label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input name="nascimento"  class="form-control pull-right datepi date" value='{{date('d/m/Y',strtotime($user->birth))}}' type="text" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Sexo</label>
                                    <select class="form-control"  name="sex">
                                        <option value="M" {{($user->sex == 'M') ? "selected" : ""}}>Masculino</option>';
                                        <option value="F" {{($user->sex == 'F') ? "selected" : ""}}>Feminino</option>';
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
