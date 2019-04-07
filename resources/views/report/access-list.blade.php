@extends("templates.report.master-report")


@section('css-view')

@stop

@section('conteudo-report')

    <table id="myTable" border="1" class="tablesorter table table-bordered table-hover dataTable tableData" role="">
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
        jQuery.extend( jQuery.fn.dataTableExt.oSort, {
            "portugues-pre": function ( data ) {
                var a = 'a';
                var e = 'e';
                var i = 'i';
                var o = 'o';
                var u = 'u';
                var c = 'c';
                var special_letters = {
                    "Á": a, "á": a, "Ã": a, "ã": a, "À": a, "à": a,
                    "É": e, "é": e, "Ê": e, "ê": e,
                    "Í": i, "í": i, "Î": i, "î": i,
                    "Ó": o, "ó": o, "Õ": o, "õ": o, "Ô": o, "ô": o,
                    "Ú": u, "ú": u, "Ü": u, "ü": u,
                    "ç": c, "Ç": c
                };
                for (var val in special_letters)
                    data = data.split(val).join(special_letters[val]).toLowerCase();
                return data;
            },
            "portugues-asc": function ( a, b ) {
                return ((a < b) ? -1 : ((a > b) ? 1 : 0));
            },
            "portugues-desc": function ( a, b ) {
                return ((a < b) ? 1 : ((a > b) ? -1 : 0));
            }
        } );

        $(document).ready(function() {
            var table = $('.tableData').DataTable({
                searching: false,
                paging: false,
                ordering: true,
                info: false,
                fixedHeader: true,
                "language": {
                    "decimal": ",",
                    "thousands": "."
                },
                "columns": [ { "type": 'portugues' },{ "type": 'portugues' },{ "type": 'portugues' },null ]
            });
        });
    </script>
@stop
