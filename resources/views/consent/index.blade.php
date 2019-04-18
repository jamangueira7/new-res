@extends("templates.master")


@section('css-view')

@stop

@section('conteudo-view')
    <section class="content container-fluid">
        {{--CONSULTAR SATATUS --}}
        <form id="myForm">
                <div class="box box-primary" id="SearchForStatus">
                    <div class="box-header with-border">
                        <h3 class="box-title">Consultar status do beneficiário</h3>
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
                            <label>*Codigo beneficiário</label>
                            <input class="col-md-6 form-control" id="codBenef" name="codBenef" type="number" value="" required />
                        </div>
                        <div class="box-footer">
                            <button class="btn btn-primary" id="ajaxSubmit">Enviar</button>
                        </div>
                    </div>
                </div>
        </form>
        {{--ATIVAR BENEFICIARIO--}}
        <form id="myForm2" method="post" enctype="multipart/form-data">
                <div class="box box-primary" id="SearchForConsent">
                    <div class="box-header with-border">
                        <h3 class="box-title">Ativar beneficiário</h3>
                    </div>

                    <div class="box-body">
                        <div class="col-md-6 form-group">
                            <label>*Codigo Unimed</label>
                            <input class="col-md-6 form-control" id="cod_unimed_ativa" disabled name="cod_unimed_ativa" type="text" value="" required />
                        </div>
                        <div class="col-md-6 form-group">
                            <label>*Codigo beneficiário</label>
                            <input class="col-md-6 form-control" id="cod_benef_ativa" disabled name="cod_benef_ativa" type="text" value="" required />
                        </div>
                        <div class="col-md-12 form-group">
                            <label>Upload de arquivo:</label>
                            <input type="file" class="form-control-file"  name="fileup" id="fileup">
                        </div>
                        <div class="box-footer col-md-6">
                            <button class="btn btn-primary" id="ajaxSubmit2">Enviar Ativação</button>
                        </div>
                        <div class="box-footer col-md-6">
                            <button class="btn btn-info" id="ajaxNewSearch">Consultar Status</button>
                        </div>
                    </div>
                </div>
        </form>
    </section>
@stop

@section('js-view')
    <script>

    jQuery(document).ready(function(){
            //SUBMIT CONSULTA STATUS
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
                        codBenef: $('#codBenef').val(),
                        codUnimed: $('#codUnimed').val()
                    },
                    success: function(result){
                        $( "#msgSuccess" ).text("");
                        $( "#msgError" ).text("");
                        $("#msgSuccess").css("display", "none");
                        $("#msgError").css("display", "none");

                        if(result.status == 'active'){
                            $( "#msgSuccess" ).show();
                            $( "#msgSuccess" ).text("Usuário já está ativo!");
                        }//active

                        if(result.status == 'inactive'){
                            $( "#msgError" ).show();
                            $( "#msgError" ).text(result.msg);
                            $("#SearchForConsent").css("display", "block");
                            $("#SearchForStatus").css("display", "none");
                            //PEGAR VALORES DO FORM DE BUSCA
                            $( "#cod_benef_ativa" ).val($('#codBenef').val());
                            $( "#cod_unimed_ativa" ).val($('#codUnimed').val());

                        }//inactive

                        if(result.status == 'erro' ){
                            $( "#msgError" ).show();
                            $( "#msgError" ).text(result.msg);
                        }//erro
                    }});
            });
            //NOVA BUSCA
            jQuery('#ajaxNewSearch').click(function(e){
                $("#SearchForConsent").css("display", "none");
                $("#SearchForStatus").css("display", "block");
            });

            //SUBIMT ATIVAR BENEFICIARIO
            jQuery('#ajaxSubmit2').click(function(e){
                e.preventDefault();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                jQuery.ajax({
                    url: "{{ url('/consent/ativacao') }}",
                    method: 'POST',
                    data: new FormData($("#myForm2")[0]),
                    dataType:'JSON',
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(result){
                        console.log(result);
                    },
                    error: function(data) {
                        console.log(data);
                    }
                });

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
