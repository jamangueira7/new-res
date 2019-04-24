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
                            @if(session('login')['level_id'] != 2)
                                <select class="form-control"  name="unimed">
                                    <option value="0">Todos</option>
                                    @foreach($unimeds as $unimed)
                                        @if($unimed->id_unimed == session('login')['unimed'])
                                            <option value="{{$unimed->id_unimed}}" selected>{{explode("-",$unimed->id_unimed)[0]}} - {{$unimed->ds_unimed}}</option>';
                                        @else
                                            <option value="{{$unimed->id_unimed}}">{{explode("-",$unimed->id_unimed)[0]}} - {{$unimed->ds_unimed}}</option>';
                                        @endif
                                    @endforeach
                                </select>
                            @else
                                <select class="form-control"  name="unimed" disabled>
                                    @foreach($unimeds as $unimed)
                                        @if($unimed->id_unimed == session('login')['unimed'])
                                            <option value="{{$unimed->id_unimed}}">{{explode("-",$unimed->id_unimed)[0]}} - {{$unimed->ds_unimed}}</option>';
                                        @endif
                                    @endforeach
                                </select>
                            @endif
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
        <form id="myForm2" enctype="multipart/form-data">
                <div class="box box-primary" id="SearchForConsent" style="display: none">
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
                            $( "#msgSuccess" ).text(result.msg);
                        }//active

                        if(result.status == 'inactive'){
                            $( "#msgError" ).show();
                            $( "#msgError" ).text(result.msg);
                            $("#SearchForConsent").css("display", "block");
                            $("#SearchForStatus").css("display", "none");
                            //PEGAR VALORES DO FORM DE BUSCA
                            var res = $('#codUnimed').val().split("-");
                            console.log(res);
                            $( "#cod_benef_ativa" ).val($('#codBenef').val());
                            $( "#cod_unimed_ativa" ).val(res[0]);

                        }//inactive

                        if(result.status == 'erro' ){
                            $( "#msgError" ).show();
                            $( "#msgError" ).text(result.msg);
                        }//erro
                    }});
            });
            //NOVA BUSCA
            jQuery('#ajaxNewSearch').click(function(e){
                e.preventDefault();
                $("#SearchForConsent").css("display", "none");
                $("#SearchForStatus").css("display", "block");
                $("#msgError").css("display", "none");
                $("#msgSuccess").css("display", "none");
                $( "#codBenef" ).val('');
                $( "#fileup" ).val('');
            });
            //APAGAR MENSAGEM DE ERRO
            $(document).ready(function() {

                $("#codBenef").keyup(function() {

                    if($("#codBenef").val()==''){
                        $("#msgError").css("display", "none");
                        $("#msgSuccess").css("display", "none");
                    }
                });

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
                        if(result.status == 'active'){
                            $( "#msgSuccess" ).show();
                            $( "#msgSuccess" ).text(result.msg);

                            $("#SearchForConsent").css("display", "none");
                            $("#SearchForStatus").css("display", "block");
                            $("#msgError").css("display", "none");
                            $( "#codBenef" ).val('');
                            $( "#fileup" ).val('');
                        }//active

                        if(result.status == 'erro' ){
                            $( "#msgError" ).show();
                            $( "#msgError" ).text(result.msg);

                            $("#SearchForConsent").css("display", "none");
                            $("#SearchForStatus").css("display", "block");
                            $("#msgSuccess").css("display", "none");
                            $( "#codBenef" ).val('');
                            $( "#fileup" ).val('');
                        }//erro
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
