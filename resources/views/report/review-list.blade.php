@extends("templates.report.master-report")

@section('css-view')

@stop

@section('conteudo-report')

    <table id="example" border="1" class="tablesorter table table-bordered table-hover dataTable tableData">
        <thead>
            <tr>
                <th class="al_center">Código</th>
                <th class="al_center">Código Beneficiários</th>
                <th class="al_center">Nome Beneficiários</th>
                <th class="al_center">Código critica</th>
                <th class="al_center">Data critica</th>
                <th class="al_center">Data atualização registro</th>
                <th class="al_center">Número sequência</th>
                <th class="al_center">Mensagem de retorno</th>
                <th class="al_center">Coluna erro</th>
            </tr>
        </thead>
        <tbody>
            @foreach($logs as $log)
                <tr>
                    <td>{{$log->id_unimed}}</td>
                    <td align="center">{{$log->id_beneficiario}}</td>
                    <td align="center">{{$log->nm_beneficiario}}</td>
                    <td align="center">{{$log->id_critica}}</td>
                    <td align="center">{{convDateTimeWithBr($log->dt_critica)}}</td>
                    <td align="center">{{convDateTimeWithBr($log->dt_atualizacao_reg)}}</td>
                    <td align="center">{{$log->nr_sequencia_reg}}</td>
                    <td align="center">{{$log->ds_erro}}</td>
                    <td align="center">{{$log->ds_coluna_erro}}</td>
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
