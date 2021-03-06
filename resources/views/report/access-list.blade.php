@extends("templates.report.master-report")


@section('css-view')

@stop

@section('conteudo-report')

    <table id="example" border="1" class="tablesorter table table-bordered table-hover dataTable tableData">
        <thead>
            <tr>
                <th class="al_center">Usuário</th>
                <th class="al_center">E-mail</th>
                <th class="al_center">Descrição</th>
                <th class="al_center sorter-shortDate dateFormat-ddmmyyyy">Data</th>
            </tr>
        </thead>
        <tbody>
            @foreach($logs as $log)
                <tr>
                    <td>{{$log->name}}</td>
                    <td>{{$log->email}}</td>
                    <td align="center">{{$log->action}}</td>
                    <td align="center">{{convDateTimeWithBr($log->created_at)}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

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
