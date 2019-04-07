@extends("templates.master")


@section('css-view')

@stop

@section('conteudo-view')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title"></h3>
                    </div>
                    <div class="box-body">
                        <div class="itwv_caixa">
                            <div class="search_permissoes">
                                <form id="j_permissoes_form" action="" method="post">
                                    <select class="j_permissoes_select_level" name="level">
                                        <option value="0" selected></option>

                                    </select>

                                </form>
                            </div>
                        </div>
                        <form class="j_form" name="" action="" method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <input type="hidden" name="AjaxFile" value="Permissoes">
                                <input type="hidden" name="AjaxAction" value="">
                                <input type="hidden" name="AjaxId" value="">
                                <input type="hidden" name="AjaxLevel" value="">
                            </div>

                            <div class="itwv_caixa">
                                <div class="no-padding table-responsive">
                                    <table class="table table-hover al_center" style="max-width: 99.99%;">
                                        <thead>
                                        <tr>
                                            <th class=""></th>
                                            <th class=""></th>
                                            <th class="al_center"><a href="#" onclick="javascript:$('.itwvACCS').each(function () {
                                                                $(this).prop('checked', (($(this).is(':checked')) ? false : true));
                                                            });
                                                            return false;">1</a></th>
                                            <th class="al_center"><a href="#" onclick="javascript:$('.itwvVISL').each(function () {
                                                                $(this).prop('checked', (($(this).is(':checked')) ? false : true));
                                                            });
                                                            return false;">2</a></th>
                                            <th class="al_center"><a href="#" onclick="javascript:$('.itwvINCL').each(function () {
                                                                $(this).prop('checked', (($(this).is(':checked')) ? false : true));
                                                            });
                                                            return false;">3</a></th>
                                            <th class="al_center"><a href="#" onclick="javascript:$('.itwvEDIT').each(function () {
                                                                $(this).prop('checked', (($(this).is(':checked')) ? false : true));
                                                            });
                                                            return false;">4</a></th>
                                            <th class="al_center"><a href="#" onclick="javascript:$('.itwvEXCL').each(function () {
                                                                $(this).prop('checked', (($(this).is(':checked')) ? false : true));
                                                            });
                                                            return false;">5</a></th>
                                            <th class="al_center"><a href="#" onclick="javascript:$('.itwvIMPR').each(function () {
                                                                $(this).prop('checked', (($(this).is(':checked')) ? false : true));
                                                            });
                                                            return false;">6></a></th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="form-group"><hr /></div>

                            <div class="itwv_caixa">
                                <div class="no-padding table-responsive">

                                    <table class="table table-hover al_center" style="max-width: 99.99%;">
                                        <thead>
                                        <tr>
                                            <th class=""></th>
                                            <th class="al_center"><a href="#" onclick="javascript:$('.itwvVSPE').each(function () {
                                                                $(this).prop('checked', (($(this).is(':checked')) ? false : true));
                                                            });
                                                            return false;">1</a></th>
                                            <th class="al_center"><a href="#" onclick="javascript:$('.itwvIPPE').each(function () {
                                                                $(this).prop('checked', (($(this).is(':checked')) ? false : true));
                                                            });
                                                            return false;">2</a></th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="box box-default">
                    <div class="box-body">
                        <button type="submit" class="btn btn-primary j_submit_form" name=""></button>
                    </div>
                </div>
            </div>
        </div>
    </section>

@stop

@section('js-view')
   <script>


   </script>
@stop
