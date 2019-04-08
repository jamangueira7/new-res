@extends("templates.master")


@section('css-view')

@stop

@section('conteudo-view')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"></h3>
                    </div>
                    <div class="box-body">
                        <div class="itwv_caixa">
                            <a class="btn btn-plus btn-sm" href="{{route('user.create')}}" title='Adicionar novo usuário'><i class="fa fa-plus"></i></a>

                            <div class="no-padding table-responsive">
                                <table class="table table-hover al_center" style="max-width: 99.99%;" id="example">
                                    <thead>
                                        <tr>
                                            <th width='300'>Nome</th>
                                            <th width='210' class="al_center">E-mail</th>
                                            <th width='150' class="al_center">Ativo</th>
                                            <th width='110' class="al_center">Detalhes</th>
                                            <th width='110' class="al_center">Excluirs</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($users as $user)
                                            <tr>
                                                <td class="al_left">{{$user->name}}</td>
                                                <td class="al_center">{{$user->email}}</td>
                                                <td class="al_center"><i class="fa {{empty($user->deleted_at) ? 'fa-check-square-o' : 'fa-square-o'}}"></i></td>
                                                <td class="al_center"><a href="{{route('user.edit',$user->id)}}"><i class=" fa fa-edit"></i></a></td>
                                                <td class="al_center">
                                                    @if(empty($user->deleted_at))
                                                        {!! Form::open(['route' => ['user.destroy', $user->id], 'method' => 'delete']) !!}
                                                            <button type="submit"
                                                                    class="btn btn-default btn-transparent" onclick="return confirm('Tem certeza que deseja desativar esse usuário?');">
                                                                <i class="fa fa-trash" aria-hidden="true"></i>
                                                            </button>
                                                        {!! Form::close() !!}
                                                    @else
                                                        {!! Form::open(['route' => ['user.restore', $user->id], 'method' => 'get']) !!}
                                                        <button type="submit"
                                                                class="btn btn-default btn-transparent" onclick="return confirm('Tem certeza que deseja ativar esse usuário?');">
                                                            <i class="fa fa-archive" aria-hidden="true"></i>
                                                        </button>
                                                        {!! Form::close() !!}
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop

@section('js-view')
   <script>

       $(function() {
           /*Tooltip*/
           $('[data-toggle="tooltip"]').tooltip();

           /*Datatable*/
           $(document).ready(function(){

               $('#example').DataTable( {
                   "searching": true,
                   "ordering": false,
                   "language": {
                       "sEmptyTable": "Nenhum registro encontrado",
                       "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
                       "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
                       "sInfoFiltered": "(Filtrados de _MAX_ registros)",
                       "sInfoPostFix": "",
                       "sInfoThousands": ".",
                       "sLengthMenu": "_MENU_ resultados por página",
                       "sLoadingRecords": "Carregando...",
                       "sProcessing": "Processando...",
                       "sZeroRecords": "Nenhum registro encontrado",
                       "sSearch": "Pesquisar",
                       "oPaginate": {
                           "sNext": "Próximo",
                           "sPrevious": "Anterior",
                           "sFirst": "Primeiro",
                           "sLast": "Último"
                       },
                       "oAria": {
                           "sSortAscending": ": Ordenar colunas de forma ascendente",
                           "sSortDescending": ": Ordenar colunas de forma descendente"
                       }
                   }
               });
           });
       });
   </script>
@stop
