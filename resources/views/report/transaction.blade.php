@extends("templates.master")


@section('css-view')

@stop

@section('conteudo-view')
   <section class="content container-fluid">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">:</h3>
            </div>
            <form action="_modulos/relatorios/transacao/view.php" method="POST" enctype="multipart/form-data" target="_blank" autocomplete="off" class='j_form_report'>
                <div class="box-body">
                    <div class="form-group">
                        <label>:</label>
                        <select name="codigo_unimed[]" multiple="multiple" class='form-control select2 j_codigo_unimed' style='width:100%;'>

                        </select><br /><label style='cursor:pointer;'><input checked type='checkbox' class='j_select_all'>&nbsp</label>
                    </div>
                    <div class="form-group">
                        <label>:</label>
                        <input name="codigo_beneficiario" type="text" class="form-control" />
                    </div>
                    <div class="form-group">
                        <label>:</label>
                        <input name="nome_beneficiario" type="text" class="form-control" />
                    </div>
                    <div class="form-group">

                        <label>:</label>
                        <input name="numero_sequencia" type="text" class="form-control" />
                    </div>
                    <div class="form-group">
                        <label>:</label>
                        <select name="nome_servico[]" multiple="multiple" class="form-control select2 j_service_unimed">

                            <option selected value="00720">00720</option>
                            <option selected value="00750">00750</option>
                            <option selected value="00770">00770</option>
                            <option selected value="00780">00780</option>
                            <option selected value="00800">00800</option>
                        </select>
                        <label style='cursor:pointer;'><input checked type='checkbox' class='j_select_all_service'></label>
                    </div>
                    <div class="form-group">
                        <label>:</label>
                        <select class="form-control" name="codigo_status">
                            <option value='0' SELECTED></option>
                            <option value='SUCESSO'></option>
                            <option value='ERRO'></option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>:</label>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input name="data_inicial" type="text" value='<?= date('01/m/Y'); ?>' class="form-control pull-right datepi date">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>:</label>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input name="data_final" type="text" value='<?= date('t/m/Y'); ?>' class="form-control pull-right datepi date">
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <input type='hidden' value='' name='pag' />
                    <input type='hidden' value='' name='module' />
                    <button type="submit" class="btn btn-primary" class='j_submit_report' name=""></button>
                </div>
            </form>
        </div>
    </section>

@stop

@section('js-view')
   <script>


   </script>
@stop
