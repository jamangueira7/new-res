@extends("templates.report.master-report")

@section('css-view')

@stop

@section('conteudo-report')

    <table id="example" border="1" class="tablesorter table table-bordered table-hover dataTable tableData">
        <thead>
            <tr>
                <th class="al_center">Nº Seq Transação</th>
                <th class="al_center">Unimed</th>
                <th class="al_center">Código Beneficiários</th>
                <th class="al_center">Nome Beneficiários</th>
                <th class="al_center">Nascimento</th>
                <th class="al_center">Mensagem</th>
                <th class="al_center">Status</th>
                <th class="al_center">Nome do serviço</th>
                <th class="al_center">Data do log</th>
                <th class="al_center">XML envio</th>
                <th class="al_center">XML retorno</th>
            </tr>
        </thead>
        <tbody>
            @foreach($logs as $log)
                <tr>
                    <td>{{$log->nr_seq_transacao}}</td>
                    <td align="center">{{$log->ds_unimed}}</td>
                    <td align="center">{{$log->id_beneficiario}}</td>
                    <td align="center">{{$log->nm_beneficiario}}</td>
                    <td align="center">{{convDateWithBr($log->dt_nascimento)}}</td>
                    <td align="center">{{$log->ds_mensagem}}</td>
                    <td align="center">{{$log->ds_status}}</td>
                    <td align="center">{{$log->nm_servico}}</td>
                    <td align="center">{{convDateTimeWithBr($log->dt_log)}}</td>
                    @if($log->xml_envio == 1)
                        <td align="center"><a href="{{route('report.transaction-xml',['seq' => $log->nr_seq_transacao, 'type' => 'ENV'])}}" target="_blank"><i class="fa fa-file-code-o"></i></a></td>
                    @else
                        <td></td>
                    @endif
                    @if($log->xml_retorno == 1)
                        <td align="center"><a href="{{route('report.transaction-xml',['seq' => $log->nr_seq_transacao, 'type' => 'RES'])}}" target="_blank"><i class="fa fa-file-code-o"></i></a></td>
                    @else
                        <td></td>
                    @endif
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
