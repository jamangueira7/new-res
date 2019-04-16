@extends("templates.master")


@section('css-view')

@stop

@section('conteudo-view')
    <section class="content container-fluid">

        <form id="myForm">
                <div class="box box-primary" id="SearchForStatus">
                    <div class="box-header with-border">
                        <h3 class="box-title">Consulta Status</h3>
                    </div>
                    <div class="box-body">
                        <div class="col-md-6 form-group">
                            <label>*Codigo Unimed</label>

                            <select class="col-md-6 form-control" id="codUnimed"  name="codUnimed">
                                @foreach($unimeds as $unimed)
                                    <option value="{{$unimed->id_unimed}}">{{explode("-",$unimed->id_unimed)[0]}} - {{$unimed->ds_unimed}}</option>';
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>*Codigo beneficiario </label>
                            <input class="col-md-6 form-control" id="codBenef" name="codBenef" type="number" value="" required />
                        </div>
                        <div class="box-footer">
                            <button class="btn btn-primary" id="ajaxSubmit">Enviar</button>
                        </div>
                    </div>
                </div>
        </form>
        <form id="myForm2">
                <div class="box box-primary" style="display:none" id="SearchForConsent">
                    <div class="box-header with-border">
                        <h3 class="box-title">Enviar consentimento</h3>
                    </div>
                    <div class="box-body">
                        <div class="col-md-12 form-group">
                            <label>Teste</label>
                            <input class="col-md-6 form-control" id="tese" name="teste" type="number" value="" required />
                        </div>
                        <div class="box-footer">
                            <button class="btn btn-primary" id="ajaxSubmit">Enviar</button>
                        </div>
                    </div>
                </div>
        </form>
    </section>
@stop

@section('js-view')
    <script>

        jQuery(document).ready(function(){
            jQuery('#ajaxSubmit').click(function(e){
                e.preventDefault();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                jQuery.ajax({
                    url: "{{ url('/consent/checkstatus') }}",
                    method: 'post',
                    data: {
                        codBenef: jQuery('#codBenef').val(),
                        codUnimed: jQuery('#codUnimed').val()
                    },
                    success: function(result){
                        $( "#msgSuccess" ).text("");
                        $( "#msgError" ).text("");
                        $("#msgSuccess").css("display", "none");
                        $("#msgError").css("display", "none");

                        if(result.status == 'active'){
                            $( "#msgSuccess" ).show();
                            $( "#msgSuccess" ).text("Usuário já está ativo!");
                        }

                        if(result.status == 'inactive'){
                            $( "#msgError" ).show();
                            $( "#msgError" ).text("Usuário inativo!");
                            $("#SearchForConsent").css("display", "block");
                            $("#SearchForStatus").css("display", "none");

                        }

                        if(result.status == 'erro' ){
                            $( "#msgError" ).show();
                            $( "#msgError" ).text(result.msg);
                        }
                    }});
            });
        });

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
