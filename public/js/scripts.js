/***************************************
############## PAG GERAL ##############
****************************************/
 //-- STR PAD
function itwvSTRPAD (itwvSTR, itwvMAX) {
  itwvSTR = itwvSTR.toString();
  return itwvSTR.length < itwvMAX ? itwvSTRPAD("0" + itwvSTR, itwvMAX) : itwvSTR;
}

// SHOW IMG
function showThumbnail(filess) {
    var url = filess.value;
    var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
    if (filess.files && filess.files[0]&& (ext == "gif" || ext == "png" || ext == "jpeg" || ext == "jpg")) {
        var reader = new FileReader();
        reader.onload = function (e) {
            document.getElementById('j_show_img').setAttribute('src', e.target.result);
        }
        reader.readAsDataURL(filess.files[0]);
    }
}

$(function(){
    /**************************************
    ############## PAG GERAL ##############
    ***************************************/
    // TABLE SORTER
    // $("#myTable").tablesorter();
    $("#myTable").tablesorter({
        dateFormat : "mmddyyyy", // set the default date format

        // or to change the format for specific columns, add the dateFormat to the headers option:
        headers: {
            0: { sorter: "shortDate" } //, dateFormat will parsed as the default above
            // 1: { sorter: "shortDate", dateFormat: "ddmmyyyy" }, // set day first format; set using class names
            // 2: { sorter: "shortDate", dateFormat: "yyyymmdd" }  // set year first format; set using data attributes (jQuery data)
        }

    });

    // MASCARA CPF OU CNPJ
    $(document).on('click', '.j_mask_cpf_cnpj', function(){
        $(this).mask('00000000000000', {reverse: true});
    });
    $(document).on('focusout', '.j_mask_cpf_cnpj', function(){
        var strLen = $(this).val().replace(/[^\d]+/g,'').length;

        if (strLen == 11) {
            $(this).removeClass('cnpj');
            $(this).addClass('cpf');
        }else {
            $(this).removeClass('cpf');
            $(this).addClass('cnpj');
        }

        $('.cpf').mask('000.000.000-00', {reverse: true});
        $('.cnpj').mask('00.000.000/0000-00', {reverse: true});
    });

    // MANDA FILTRO
    $('.j_manda_filtro').click(function(){
        var href = $(this).attr('href');

        if (!href) {
            var hrefNali = $(this).find('a').attr('href');
            href = hrefNali;
        }

        $('.j_filtro').attr('action', href);
        $('.j_filtro').submit();

        return false;
    });

    // CHECK MAX E MIN DO INPUT
    $('.j_check_max_min').change(function(){
        var min = parseInt($(this).attr('min'));
        var max = parseInt($(this).attr('max'));
        var val = parseInt($(this).val());

        if (val < max && val > min) {
            return true;
        }

        if (val < min) {
             $(this).val(min);
        }else if (val > max) {
            $(this).val(max);
        }
    });

    // DEIXA OS INPUTS TUDO MAIUSCULO
    $('input[type="text"], input[type="email"], textarea').keyup(function(){
        $(this).val($(this).val().toUpperCase());
    });
    $('input[type="text"], input[type="email"], textarea').focusout(function(){
        $(this).val($(this).val().toUpperCase());
    });

    // MULTI SELECT - SELECIONA TUDO NO SELECT
    $('.j_multi_select').click(function(){
        $('.j_sel_multi option').each(function() {
            var opt = $(this).prop('selected',true);
        });
    });

    // LOGIN
    $('.j_form_login').submit(function(){
        var AjaxData = $(this).serialize();

        $.ajax({
            method: 'POST',
            url: '_ajax/Login.ajax.php',
            data: AjaxData,
            dataType: 'json',
            beforeSend: function () {
                $('.load_alpha').fadeIn('fast');
            },
            success: function (data) {
                $('.load_alpha').fadeOut('fast');

                TriggerError(data.triggerType, data.triggerMsg);

                if(data.triggerType == 'trigger_success'){
                    setTimeout(function(){ location.href = "painel.php"; }, 2000);
                }
            },
            error: function(){
                $('.load_alpha').fadeOut('fast');
                TriggerError('trigger_error', '<b>Erro</b>');
            }
        });
        return false;
    });

    // JQuery UI DatePicker
    function DataPi(){
        $(".datepi").datepicker({
            dateFormat: 'dd/mm/yy',
            dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado'],
            dayNamesMin: ['D','S','T','Q','Q','S','S','D'],
            dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb','Dom'],
            monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
            monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],
            nextText: 'Próximo',
            prevText: 'Anterior'
        });
    }
    DataPi();

    // MODAL IFRAME
    function AbrirModalIframe(srcIframe){
        var ContentModal = "<iframe src='"+srcIframe+"?iframe=true'></iframe>"
        $('.j_modal_content_iframa').html(ContentModal);
        $('.j_modal_iframe').fadeIn();
    }
    $('.j_abrir_modal_iframe').click(function(){
        if ($(this).attr('for')) {
            AbrirModalIframe($(this).attr('for'));
        }
    });
    $('.j_close_modal_iframe').click(function(){
        $('.j_modal_iframe').fadeOut();
    });

    // MODAL CONTENT
    $('.j_abrir_modal').click(function(){
        $('.j_modal').fadeIn();
    });

    // MODAL ERRO
    $('.j_close_modal').click(function(){
        $('.j_modal_f').fadeOut();
    });

    // FECHAR MODAL DE ERRO
    $('.trigger_modal').click(function(){
        $('.trigger_modal').fadeOut();
    })

    // MODAL CONFIRM
    function AbrirModalConfirm(LinkNovo, LinkAtual){
        $('.j_confirm_novo').attr('href', LinkNovo);
        $('.j_confirm_atual').attr('href', LinkAtual);
        $('.j_modal_confirm').fadeIn();
    }

    // CHAMA MENSAGEM
    function TriggerError(triggerType, triggerMsg) {
        $('.trigger_ajax').removeClass('trigger_none');
        $('.trigger_ajax').removeClass('trigger_success');
        $('.trigger_ajax').removeClass('trigger_info');
        $('.trigger_ajax').removeClass('trigger_alert');
        $('.trigger_ajax').removeClass('trigger_error');

        $('.trigger_ajax').addClass(triggerType);
        $('.trigger_modal').fadeIn();
        $('.trigger_ajax').html(triggerMsg);
    }

    // VALIDA CAMPOS
    // data, telefone, celular, CNPJ e CPF,
    function ValidaData(data) {
        if (data.length < 10) {
            return false;
        }

        var DZ = data.split('/');

        // VALIDA MES
        if (DZ[1] < 1 || DZ[1] > 12) {
            return false;
        }

        // VALIDA DATA
        var dm = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
        if (dm[parseInt(DZ[1]) - 1] < DZ[0] || dm[parseInt(DZ[1]) - 1] < 1) {
            // alert(dm[parseInt(DZ[1]) - 1]+' < '+DZ[0]+' || '+dm[parseInt(DZ[1]) - 1]+' < '+'1');
            return false;
        }

        return true;
    }
    function ValidaTell(value) {
        value = value.replace("(", "");
        value = value.replace(")", "");
        value = value.replace("-", "");
        value = value.replace(" ", "").trim();
        if (value == '0000000000' || value == '00000000000') {
            return false;
        }
        if (value.length < 10 || value.length > 11) {
            return false;
        }
        return true;
    }

    function ValidaCPF(value) {
        var cpf = value.replace(/[^\d]+/g,'');

        if (cpf.length != 11 ||
            cpf == "00000000000" ||
            cpf == "11111111111" ||
            cpf == "22222222222" ||
            cpf == "33333333333" ||
            cpf == "44444444444" ||
            cpf == "55555555555" ||
            cpf == "66666666666" ||
            cpf == "77777777777" ||
            cpf == "88888888888" ||
            cpf == "99999999999")
            return false;

        add = 0;

        for (i = 0; i < 9; i++)
                add += parseInt(cpf.charAt(i)) * (10 - i);
        rev = 11 - (add % 11);
        if (rev == 10 || rev == 11)
            rev = 0;
        if (rev != parseInt(cpf.charAt(9)))
            return false;
        add = 0;
                for (i = 0; i < 10; i++)
                add += parseInt(cpf.charAt(i)) * (11 - i);
        rev = 11 - (add % 11);
        if (rev == 10 || rev == 11)
            rev = 0;
        if (rev != parseInt(cpf.charAt(10)))
            return false;
        return true;
    }

    function ValidaCNPJ(value) {
        var cnpj = value.replace(/[^\d]+/g,'');

        if(cnpj == '') return false;

        if (cnpj.length != 14)
        return false;

        // Elimina CNPJs invalidos conhecidos
        if (cnpj == "00000000000000" ||
        cnpj == "11111111111111" ||
        cnpj == "22222222222222" ||
        cnpj == "33333333333333" ||
        cnpj == "44444444444444" ||
        cnpj == "55555555555555" ||
        cnpj == "66666666666666" ||
        cnpj == "77777777777777" ||
        cnpj == "88888888888888" ||
        cnpj == "99999999999999")
        return false;

        // Valida DVs
        tamanho = cnpj.length - 2
        numeros = cnpj.substring(0,tamanho);
        digitos = cnpj.substring(tamanho);
        soma = 0;
        pos = tamanho - 7;
        for (i = tamanho; i >= 1; i--) {
            soma += numeros.charAt(tamanho - i) * pos--;
            if (pos < 2)
            pos = 9;
        }
        resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
        if (resultado != digitos.charAt(0))
        return false;

        tamanho = tamanho + 1;
        numeros = cnpj.substring(0,tamanho);
        soma = 0;
        pos = tamanho - 7;
        for (i = tamanho; i >= 1; i--) {
            soma += numeros.charAt(tamanho - i) * pos--;
            if (pos < 2)
            pos = 9;
        }
        resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
        if (resultado != digitos.charAt(1))
        return false;

        return true;
    }

    function ValidaCampos(Form) {
        $('input').removeClass('not-validate');
        var f = 0;
        var DATE = Form.find('.date');
        $.each(DATE, function() {
            if ($(this).val()) {
                if (!ValidaData($(this).val())) {
                    msgT = '<b>MSG: </b> Campo de Data Inválido';
                    typeT = 'trigger_alert';
                    TriggerError(typeT, msgT);

                    $(this).focus();
                    $(this).addClass('not-validate');
                    f = 1;
                };
            }
        });
        if (f == 1) {
            return false;
        }

        // TELEFONE / CELULAR
        var TELL = Form.find('.phone_with_ddd');
        $.each(TELL, function() {
            if ($(this).val()) {
                if (!ValidaTell($(this).val())) {
                    msgT = '<b>MSG: </b> Campo de Celular/Telefone Inválido';
                    typeT = 'trigger_alert';
                    TriggerError(typeT, msgT);

                    $(this).focus();
                    $(this).addClass('not-validate');
                    f = 1;
                };
            }
        });
        if (f == 1) {
            return false;
        }

        // CPF
        var CPF = Form.find('.cpf');
        $.each(CPF, function() {
            if ($(this).val()) {
                if (!ValidaCPF($(this).val())) {
                    msgT = '<b>MSG: </b> Campo de CPF Inválido';
                    typeT = 'trigger_alert';
                    TriggerError(typeT, msgT);

                    $(this).focus();
                    $(this).addClass('not-validate');
                    f = 1;
                };
            }
        });
        if (f == 1) {
            return false;
        }

        // CNPJ
        var CNPJ = Form.find('.cnpj');
        $.each(CNPJ, function() {
            if ($(this).val()) {
                if (!ValidaCNPJ($(this).val())) {
                    msgT = '<b>MSG: </b> Campo de CNPJ Inválido';
                    typeT = 'trigger_alert';
                    TriggerError(typeT, msgT);

                    $(this).focus();
                    $(this).addClass('not-validate');
                    f = 1;
                };
            }
        });
        if (f == 1) {
            return false;
        }

        return true;
    }

    // SUBMIT FORM
    $('.j_form').submit(function(){
        var AjaxData = $(this).serialize();
        var AjaxFile = $(this).find('input[name="AjaxFile"]').val();
        var AjaxAction = $(this).find('input[name="AjaxAction"]').val();
        var AjaxForm = $(this);

        if (!ValidaCampos(AjaxForm)) {
            return false;
        }

        var RefreshSalve = $('.j_refresh').val();

        var FormIframe = $('.j_form_iframe').val();
        if (FormIframe) {
            FormIframe = '../../';
        }else {
            FormIframe = '';
        }

        $.ajax({
            method: 'POST',
            url: FormIframe + '_ajax/' + AjaxFile + '.ajax.php',
            data: AjaxData,
            dataType: 'json',
            beforeSend: function () {
                $('.load_alpha').fadeIn('fast');
            },
            success: function (data) {
                $('.load_alpha').fadeOut('fast');

                TriggerError(data.triggerType, data.triggerMsg);

                if(data.triggerType == 'trigger_success'){
                    if (data.registerNovo && data.registerAtual) {
                        if (FormIframe) {
                            location.reload();
                        }
                        AbrirModalConfirm(data.registerNovo, data.registerAtual);
                    }else{
                        try {
                            AjaxForm[0].reset();
                        } catch (e) {
                            alert(e.message);
                        }
                        setTimeout(function(){ location.reload(); }, 2000);
                    }
                }
            },
            error: function(){
                $('.load_alpha').fadeOut('fast');
                TriggerError('trigger_error', '<b>Erro</b>');
            }
        });
        return false;
    });

    // SUBMIT BOTAO FORA DO FORM
    $('.j_submit_form').click(function(){
        $('.j_form').submit();
    });

    // UPLOAD DE IMG
    $('.j_up_img').change(function(){
        var AjaxData = new FormData();
        AjaxData.append('file', $(this).prop('files')[0]);
        AjaxData.append('AjaxAction', 'Imagem');
        AjaxData.append('AjaxFile', 'File');
        var AjaxInput = $(this);

        $.ajax({
            method: 'POST',
            url: '_ajax/File.ajax.php',
            data: AjaxData,
            dataType: 'json',
            beforeSend: function () {
                $('.load_alpha').fadeIn('fast');
            },
            success: function (data) {
                $('.load_alpha').fadeOut('fast');

                TriggerError(data.triggerType, data.triggerMsg);

                $('.j_name_foto').attr('value', data.file);
            },
            error: function(){
                $('.load_alpha').fadeOut('fast');
                TriggerError('trigger_error', '<b>Erro</b>');
            },
            processData: false,
            cache: false,
            contentType: false
        });
    });

    // UPLOAD ARQUIVO
    $('.j_up_file').change(function(){
        var AjaxData = new FormData();
        AjaxData.append('AjaxFile', 'File');
        AjaxData.append('AjaxAction', 'UpFile');
        AjaxData.append('AjaxTipoDoc', $('#Ajax_AjaxTipoDoc').val());
        // AjaxData.append('AjaxCodigoRegistro', $('#Ajax_AjaxCodigoRegistro').val());
        AjaxData.append('AjaxTabela', $('#Ajax_AjaxTabela').val());

        var FileQTD = $(this).prop('files');

        for (iR = 0; iR < FileQTD.length; iR++){
            AjaxData.append('file[]', $(this).prop('files')[iR]);
        }


        $.ajax({
            method: 'POST',
            url: '_ajax/File.ajax.php',
            data: AjaxData,
            dataType: 'json',
            beforeSend: function () {
                $('.load_alpha').fadeIn('fast');
            },
            success: function (data) {
                $('.load_alpha').fadeOut('fast');

                TriggerError(data.triggerType, data.triggerMsg);

                for (i=0; i < data.filePrevName.length; i++) {
                    $(".j_success_file").append('<tr>'+
                    '<td>'+data.TypeDoc[i]+'<input type="hidden" name="codigo_tipo_documento[]" value="'+data.IdTypeDoc[i]+'"><input type="hidden" name="tabela[]" value="'+data.Table[i]+'"></td>'+
                    '<td><a href="files/'+data.Table[i]+'/'+data.fileNewName[i]+'" target="_blank">'+data.filePrevName[i]+'</a><input type="hidden" name="nome_atual[]" value="'+data.fileNewName[i]+'"><input type="hidden" name="nome_original[]" value="'+data.filePrevName[i]+'"></td>'+
                    '<td>'+data.Date[i]+'</td>'+
                    '<td>'+data.Size[i]+' KB</td>'+
                    '<td><button class="btn btn-box-tool j_file_dell" type="button" id="" for="'+data.fileNewName[i]+'" name="button"><i class=" fa fa-trash"></i></button></td>'+
                    '</tr>');
                }
            },
            error: function(){
                $('.load_alpha').fadeOut('fast');
                TriggerError('trigger_error', '<b>Erro</b>');
            },
            processData: false,
            cache: false,
            contentType: false
        });
    });

    // DELETE FILE
    var delTRFile;

    $(document).on('click', '.j_file_dell', function(){
        delTRFile = $(this).parent().parent();

        $('.j_delete_yes_file').attr('for', $(this).attr('for'));
        $('.j_delete_yes_file').attr('id', $(this).attr('id'));
        $('.j_modal_delete_file').fadeIn();
    });

    $(document).on('click', '.j_delete_no_file', function(){
        $('.j_modal_delete_file').fadeOut();
        return false;
    });

    $(document).on('click', '.j_delete_yes_file', function() {
        var AjaxData = new FormData();
        AjaxData.append('AjaxFile', 'File');
        AjaxData.append('AjaxAction', 'DellFile');
        AjaxData.append('AjaxTabela', $('#Ajax_AjaxTabela').val());
        AjaxData.append('IdFileDell', $(this).attr('id'));

        if($(this).attr('id') == ''){
            AjaxData.append('NameFileDell', $(this).attr('for'));
        }

        $.ajax({
            method: 'POST',
            url: '_ajax/File.ajax.php',
            data: AjaxData,
            dataType: 'json',
            beforeSend: function () {
                $('.load_alpha').fadeIn('fast');
            },
            success: function (data) {
                $('.load_alpha').fadeOut('fast');

                TriggerError(data.triggerType, data.triggerMsg);

                delTRFile.remove();
            },
            error: function(){
                $('.load_alpha').fadeOut('fast');
                TriggerError('trigger_error', '<b>Erro</b>');
            },
            processData: false,
            cache: false,
            contentType: false
        });

        $('.j_modal_delete_file').fadeOut();
        return false;
    });

    // CEP WEBSERVICE
    $('.j_cep_webs').change(function(){
        var CEP = $(this).val();
        CEP = CEP.replace("-", "");

        var URL = 'http://viacep.com.br/ws/'+CEP+'/json/unicode/';

        $.ajax({
            url: URL,
            dataType: 'json',
            beforeSend: function () {
                $('.load_alpha').fadeIn('fast');
            },
            success: function (data) {
                $('.load_alpha').fadeOut('fast');

                if(!data.erro){
                    $('.j_cep_webs_rua').val(data.logradouro.toUpperCase());
                    $('.j_cep_webs_bairro').val(data.bairro.toUpperCase());
                    $('.j_cep_webs_cidade').val(data.localidade.toUpperCase());
                    $('.j_cep_webs_uf').val(data.uf.toUpperCase());

                    $('.j_cep_webs_numero').focus();
                }
            },
            error: function(){
                $('.load_alpha').fadeOut('fast');
            },
        });
    });

    // DELETE ROW
    var delTR;

    $('.j_delete_row').click(function(){
        delTR = $(this).parent().parent();
        var AjaxFile = $(this).attr('for');
        var IdDelete = $(this).attr('id');

        $('.j_delete_yes').attr('for', AjaxFile);
        $('.j_delete_yes').attr('id', IdDelete);
        $('.j_modal_delete').fadeIn();
        return false;
    })

    $('.j_delete_no').click(function(){
        $('.j_modal_delete').fadeOut();
        return false;
    });

    $('.j_delete_yes').click(function(){
        $('.j_modal_delete').fadeOut();
        var AjaxFile = $(this).attr('for');
        // var RefreshDell = $(this).attr('rel');
        var FormIframe = $('.j_form_iframe').val();
        if (FormIframe) {
            FormIframe = '../../';
        }else {
            FormIframe = '';
        }

        var AjaxData = new FormData();
        AjaxData.append('idDelete', $(this).attr('id'));
        AjaxData.append('AjaxFile', $(this).attr('for'));
        AjaxData.append('AjaxAction', 'Delete');

        $.ajax({
            method: 'POST',
            url: FormIframe + '_ajax/'+AjaxFile+'.ajax.php',
            data: AjaxData,
            dataType: 'json',
            beforeSend: function () {
                $('.load_alpha').fadeIn('fast');
            },
            success: function (data) {
                $('.load_alpha').fadeOut('fast');

                TriggerError(data.triggerType, data.triggerMsg);

                if(data.triggerType == 'trigger_success'){
                    delTR.remove();

                    // if (RefreshDell) {
                    //     setTimeout(function(){ location.href = RefreshDell; }, 2000);
                    // }
                }
            },
            error: function(){
                $('.load_alpha').fadeOut('fast');
                TriggerError('trigger_error', '<b>Erro</b>');
            },
            processData: false,
            cache: false,
            contentType: false
        });

        return false;
    });

    // MENSAGEM
    // class="j_delete_row " rel="Deseja deletar todas as parcelas dessa conta ?"
    $('.j_inform_action').click(function(){
        $('.j_msg_inform').html($(this).attr('rel'));
    });

    /*****************************************
    ############## PAG CLIENTES ##############
    ******************************************/
    // SEARCH CLIENTE ATIVO
    $('.j_search_select_cliente').change(function(){
        var itwvSelect = $(this).val();

        if(itwvSelect == 'ativo'){
            $('.j_search_cliente').html('<select name="search">'+
            '<option value="1">SIM</option>'+
            '<option value="2">NAO</option>'+
            '</select>');
        }else{
            $('.j_search_cliente').html('<input name="search" type="text" value=""/>');

        }
    });

    // TABELA PLUS
    $('.j_table_plus_contato').click(function(){
        $('.j_body_table_plus_contato').append('<tr>'+
        '<td><input class="form-control" type="text" name="nomecontato[]" value=""></td>'+
        '<td><input class="form-control" type="text" name="telefonecontato[]" value=""></td>'+
        '<td><input class="form-control" type="text" name="celularcontato[]" value=""></td>'+
        '<td><input class="form-control" type="text" name="departamentocontato[]" value=""></td>'+
        '<td><input class="form-control" type="email" name="emailcontato[]" value=""></td>'+
        '<td><button class="btn btn-box-tool j_table_plus_contato_del" type="button" name="button"><i class=" fa fa-trash"></i></button></td>'+
        '</tr>');
    });

    $(document).on('click', '.j_table_plus_contato_del', function() {
        var delTR = $(this).parent().parent();
        delTR.remove();
    });

    // ENDERECO DE COBRANCA
    $('.j_local_cobranca').change(function(){
        if($(this).val() == 0){
            $('.j_c_cep').val($('.j_cep').val());
            $('.j_c_pais').val($('.j_pais').val());
            $('.j_c_cidade').val($('.j_cidade').val());
            $('.j_c_estado').val($('.j_estado').val());
            $('.j_c_endereco').val($('.j_endereco').val());
            $('.j_c_bairro').val($('.j_bairro').val());
            $('.j_c_complemento').val($('.j_complemento').val());
            $('.j_c_numero').val($('.j_numero').val());
        }else{
            $('.j_c_cep').val('');
            $('.j_c_pais').val('');
            $('.j_c_cidade').val('');
            $('.j_c_estado').val('');
            $('.j_c_endereco').val('');
            $('.j_c_bairro').val('');
            $('.j_c_complemento').val('');
        }
    });

    // AUTO-COMPLETE
    var LabelPC;
    var LabelHP;
    var TipoAuto;
    var LabelFuncionario;
    var LabelParceiro;
    var LabelFornecedor;
    var LabelDarf;
    var LabelCliente;

    var AutocThis;

    function AutoComplet(This){
        var itwvIPTID = This.attr('id');

        if (TipoAuto == 'HistoricoPadrao') {
            This.autocomplete({
                source: function(req, response) {
                    $.ajax({
                        method: "POST",
                        url: '_ajax/AutoCHP.ajax.php',
                        dataType: "json",
                        data: { term: $.trim($('#' + itwvIPTID).val()) },
                        success: function( data ) {
                            var re = $.ui.autocomplete.escapeRegex(req.term);
                            var matcher = new RegExp( "^" + re, "i" );
                            response($.grep(data, function(item) {
                                if (matcher.test(item.value) == false) {
                                    return { id: 0, label: "Nenhum registro encontrado!" };
                                } else{
                                    return matcher.test(item.value);
                                }
                            }));
                        }
                    });
                },
                minLength: 1,
                select: function (event, itwvJSON) {
                    if (itwvJSON.item.id != 0) {
                        $("#j_codigo_padrao").val(itwvJSON.item.value);
                        $("#j_codigo_historico_p").val(itwvJSON.item.id);
                        $("#j_descricao_label").val(itwvJSON.item.descricao);
                        LabelHP = itwvJSON.item.label;
                    } else
                    return false;
                }
            });
        }else if(TipoAuto == 'PlanoDeContas'){
            This.autocomplete({
                source: function(req, response) {
                    $.ajax({
                        method: "POST",
                        url: '_ajax/AutoC.ajax.php',
                        dataType: "json",
                        data: { term: $.trim($('#' + itwvIPTID).val()) },
                        success: function( data ) {
                            var re = $.ui.autocomplete.escapeRegex(req.term);
                            var matcher = new RegExp( "^" + re, "i" );
                            response($.grep(data, function(item) {
                                if (matcher.test(item.value) == false) {
                                    return { id: 0, label: "Nenhum registro encontrado!" };
                                } else{
                                    return matcher.test(item.value);
                                }
                            }));
                        }
                    });
                },
                minLength: 1,
                select: function (event, itwvJSON) {
                    if (itwvJSON.item.id != 0) {
                        $("#j_codigo_plano_contas").val(itwvJSON.item.codigo);
                        $("#j_numero_conta").val(itwvJSON.item.id);
                        $("#j_tipo_contas_label").val(itwvJSON.item.label);
                        LabelPC = itwvJSON.item.label;
                    } else
                    return false;
                }
            });
        }else if(TipoAuto == 'Funcionario'){
            This.autocomplete({
                source: function(req, response) {
                    $.ajax({
                        method: "POST",
                        url: '_ajax/AutoCFuncionario.ajax.php',
                        dataType: "json",
                        data: { term: $.trim($('#' + itwvIPTID).val()) },
                        success: function( data ) {
                            var re = $.ui.autocomplete.escapeRegex(req.term);
                            var matcher = new RegExp( "^" + re, "i" );
                            response($.grep(data, function(item) {
                                if (matcher.test(item.value) == false) {
                                    return { id: 0, label: "Nenhum registro encontrado!" };
                                } else{
                                    return matcher.test(item.value);
                                }
                            }));
                        }
                    });
                },
                minLength: 1,
                select: function (event, itwvJSON) {
                    if (itwvJSON.item.id != 0) {
                        $("#j_codigo_funcionario").val(itwvJSON.item.codigo);
                        $("#j_value_funcionario").val(itwvJSON.item.value);
                        LabelFuncionario = itwvJSON.item.label;
                    } else
                    return false;
                }
            });
        }else if(TipoAuto == 'Fornecedor'){
            This.autocomplete({
                source: function(req, response) {
                    $.ajax({
                        method: "POST",
                        url: '_ajax/AutoCFornecedor.ajax.php',
                        dataType: "json",
                        data: { term: $.trim($('#' + itwvIPTID).val()) },
                        success: function( data ) {
                            var re = $.ui.autocomplete.escapeRegex(req.term);
                            var matcher = new RegExp( "^" + re, "i" );
                            response($.grep(data, function(item) {
                                if (matcher.test(item.value) == false) {
                                    return { id: 0, label: "Nenhum registro encontrado!" };
                                } else{
                                    return matcher.test(item.value);
                                }
                            }));
                        }
                    });
                },
                minLength: 1,
                select: function (event, itwvJSON) {
                    if (itwvJSON.item.id != 0) {
                        $("#j_codigo_fornecedor").val(itwvJSON.item.codigo);
                        $("#j_value_fornecedor").val(itwvJSON.item.value);
                        LabelFornecedor = itwvJSON.item.label;
                    } else
                    return false;
                }
            });
        }else if(TipoAuto == 'Parceiro'){
            This.autocomplete({
                source: function(req, response) {
                    $.ajax({
                        method: "POST",
                        url: '_ajax/AutoCParceiro.ajax.php',
                        dataType: "json",
                        data: { term: $.trim($('#' + itwvIPTID).val()) },
                        success: function( data ) {
                            var re = $.ui.autocomplete.escapeRegex(req.term);
                            var matcher = new RegExp( "^" + re, "i" );
                            response($.grep(data, function(item) {
                                if (matcher.test(item.value) == false) {
                                    return { id: 0, label: "Nenhum registro encontrado!" };
                                } else{
                                    return matcher.test(item.value);
                                }
                            }));
                        }
                    });
                },
                minLength: 1,
                select: function (event, itwvJSON) {
                    if (itwvJSON.item.id != 0) {
                        $("#j_codigo_parceiro").val(itwvJSON.item.codigo);
                        $("#j_value_parceiro").val(itwvJSON.item.value);
                        LabelParceiro = itwvJSON.item.label;
                    } else
                    return false;
                }
            });
        }else if(TipoAuto == 'Darf'){
            This.autocomplete({
                source: function(req, response) {
                    $.ajax({
                        method: "POST",
                        url: '_ajax/AutoCDarf.ajax.php',
                        dataType: "json",
                        data: { term: $.trim($('#' + itwvIPTID).val()) },
                        success: function( data ) {
                            var re = $.ui.autocomplete.escapeRegex(req.term);
                            var matcher = new RegExp( "^" + re, "i" );
                            response($.grep(data, function(item) {
                                if (matcher.test(item.value) == false) {
                                    return { id: 0, label: "Nenhum registro encontrado!" };
                                } else{
                                    return matcher.test(item.value);
                                }
                            }));
                        }
                    });
                },
                minLength: 1,
                select: function (event, itwvJSON) {
                    if (itwvJSON.item.id != 0) {
                        $("#j_codigo_darf").val(itwvJSON.item.codigo);
                        $("#j_value_darf").val(itwvJSON.item.value);
                        LabelDarf = itwvJSON.item.label;
                    } else
                    return false;
                }
            });
        }else if(TipoAuto == 'Cliente'){
            This.autocomplete({
                source: function(req, response) {
                    $.ajax({
                        method: "POST",
                        url: '_ajax/AutoCCliente.ajax.php',
                        dataType: "json",
                        data: { term: $.trim($('#' + itwvIPTID).val()) },
                        success: function( data ) {
                            var re = $.ui.autocomplete.escapeRegex(req.term);
                            var matcher = new RegExp( "^" + re, "i" );
                            response($.grep(data, function(item) {
                                if (matcher.test(item.value) == false) {
                                    return { id: 0, label: "Nenhum registro encontrado!" };
                                } else{
                                    return matcher.test(item.value);
                                }
                            }));
                        }
                    });
                },
                minLength: 1,
                select: function (event, itwvJSON) {
                    if (itwvJSON.item.id != 0) {
                        $("#j_codigo_cliente").val(itwvJSON.item.codigo);
                        $("#j_value_cliente").val(itwvJSON.item.value);
                        LabelCliente = itwvJSON.item.label;
                    } else
                    return false;

                    AttProjetos();
                }
            });
        }
    }
    $(document).on("focus", '.j_autoc', function(){
        if (!$(this).attr('for')) {
            TipoAuto = 'PlanoDeContas';
        }else {
            TipoAuto = $(this).attr('for');
        }

        AutoComplet($(this));
    });

    $(document).on('click', '.ui-menu-item-wrapper', function(){
        if (LabelHP) {
            $("#j_descricao_label").val(LabelHP);
        }

        if (LabelPC) {
            $("#j_tipo_contas_label").val(LabelPC);
        }

        if (LabelFuncionario) {
            $("#j_nome_funcionario").val(LabelFuncionario);
        }

        if (LabelParceiro) {
            $("#j_nome_parceiro").val(LabelParceiro);
        }

        if (LabelFornecedor) {
            $("#j_nome_fornecedor").val(LabelFornecedor);
        }

        if (LabelDarf) {
            $("#j_nome_darf").val(LabelDarf);
        }

        if (LabelCliente) {
            $("#j_nome_cliente").val(LabelCliente);
            if (AutocThis) {
                AutocThis.val(LabelCliente);
            }
        }
    })

    $('.j_canal_comunicacao').change(function(){
        var IdComunicacao = $(this).val();

        if(IdComunicacao == 7){
            $('.j_canal_comunicacao_descricao').html('<label>Feira ou Evento</label>'+
            '<input class="form-control" name="codigo_canal_comunicacao_decricao" type="text" value=""/>');
        }else if (IdComunicacao == 4) {
            $('.j_canal_comunicacao_descricao').html('<label>Indicado por</label>'+
            '<input class="form-control" name="codigo_canal_comunicacao_decricao" type="text" value=""/>');
        }else if (IdComunicacao == 5) {
            var AjaxData = new FormData();
            AjaxData.append('AjaxFile', 'CanalComunicacao');
            AjaxData.append('AjaxAction', 'SelectReturn');

            $.ajax({
                method: 'POST',
                url: '_ajax/CanalComunicacao.ajax.php',
                data: AjaxData,
                dataType: 'json',
                beforeSend: function () {
                    $('.load_alpha').fadeIn('fast');
                },
                success: function (data) {
                    $('.load_alpha').fadeOut('fast');

                    var Select = '<label>Parceiro</label>'+
                    '<select class="form-control" name="codigo_parceiro">';

                    $.each(data, function(key, valor) {
                        Select += "<option value='" + valor.codigo + "'>" + valor.razaosocial + "</option>";
                    });

                    Select += '</select>';

                    $('.j_canal_comunicacao_descricao').html(Select);
                },
                error: function(){
                    $('.load_alpha').fadeOut('fast');
                    TriggerError('trigger_error', '<b>Erro</b>');
                },
                processData: false,
                cache: false,
                contentType: false
            });
        }else{
            $('.j_canal_comunicacao_descricao').html("");
        }
    });

    /***************************************
    ############ PAG PERMISSOES ############
    ****************************************/
    $('.j_permissoes_select_level').change(function(){
        $('.j_permissoes_select_func').remove();
        $("#j_permissoes_form").submit();
    });
    $('.j_permissoes_select_func').change(function(){
        $("#j_permissoes_form").submit();
    });

    /**************************************
    ########## PAG FUNCIONARIOS ##########
    ***************************************/
    $(document).on('change', '.j_select_search_funcionario', function(){
        if ($(this).val() == 'nascimento') {
            $('.j_search_funcionario').html('<select class="" name="search">'+
            '<option value="1">Janeiro</option>'+
            '<option value="2">Fevereiro</option>'+
            '<option value="3">Março</option>'+
            '<option value="4">Abril</option>'+
            '<option value="5">Maio</option>'+
            '<option value="6">Junho</option>'+
            '<option value="7">Julho</option>'+
            '<option value="8">Agosto</option>'+
            '<option value="9">Setembro</option>'+
            '<option value="10">Outubro</option>'+
            '<option value="11">Novembro</option>'+
            '<option value="12" >Dezembro</option>'+
            '</select>');
        }else if ($(this).val() == 'ativo') {
            $('.j_search_funcionario').html('<select class="" name="search">'+
            '<option value="1">Sim</option>'+
            '<option value="2">Nao</option>'+
            '</select>');
        }else{
            $('.j_search_funcionario').html('<input name="search" type="text" value=""/>');
        }
    });

    $(document).on('click', '.j_table_plus_bloqueio', function(){
        $('.j_body_table_plus_bloqueio').append('<tr>'+
            '<td>'+
                '<!-- ITEM -->'+
                '<div class="item_bloqueio">'+
                    '<button class="btn btn-box-tool j_table_plus_bloqueio_del" type="button" name="button"><i class=" fa fa-trash"></i></button>'+
                    '<div class="form-group al_left">'+
                        '<label>Data</label>'+
                        '<div class="row">'+
                            '<div class="col-xs-6 al_left">'+
                                '<span>* De</span>'+
                                '<div class="input-group">'+
                                    '<div class="input-group-addon">'+
                                        '<i class="fa fa-calendar"></i>'+
                                    '</div>'+
                                    '<input name="bloqDataDe[]" class="form-control pull-right datepi date" value="" type="text" required>'+
                                '</div>'+
                            '</div>'+
                            '<div class="col-xs-6 al_left">'+
                                '<span>* Ate</span>'+
                                '<div class="input-group">'+
                                    '<div class="input-group-addon">'+
                                        '<i class="fa fa-calendar"></i>'+
                                    '</div>'+
                                    '<input name="bloqDataAte[]" class="form-control pull-right datepi date" value="" type="text" required>'+
                                '</div>'+
                            '</div>'+
                        '</div>'+
                    '</div>'+

                    '<div class="form-group al_left">'+
                        '<label>Horario</label>'+
                        '<div class="row">'+
                            '<div class="col-xs-6 al_left">'+
                                '<span>* De</span>'+
                                '<input class="form-control timeSimple" type="text" name="bloqHorarioDe[]" value="" required>'+
                            '</div>'+
                            '<div class="col-xs-6 al_left">'+
                                '<span>* Ate</span>'+
                                '<input class="form-control timeSimple" type="text" name="bloqHorarioAte[]" value="" required>'+
                            '</div>'+
                        '</div>'+
                    '</div>'+

                    '<div class="form-group al_left">'+
                        '<label>Motivo</label>'+
                        '<textarea class="form-control" name="bloqMotivo[]" rows="8" cols="80"></textarea>'+
                    '</div>'+
                '</div>'+
                '<!-- /ITEM -->'+
            '</td>'+
        '</tr>');
        $('.date').mask('00/00/0000');
        $('.timeSimple').mask('00:00');
        DataPi();
    });

    $(document).on('click', '.j_table_plus_bloqueio_del', function(){
        var delTR = $(this).parent().parent();
        delTR.remove();
    });

    /**************************************
    ########## PAG CONTAS A PAGAR ##########
    ***************************************/
    // FUNCINARIO MODAL
    function CarregarTableFuncionarioAjax(WhereInput){
        var AjaxData = new FormData();
        if (WhereInput) {
            AjaxData.append('WhereInput', WhereInput);
        }
        AjaxData.append('AjaxFile', 'SelectModal');
        AjaxData.append('AjaxAction', 'Funcionario');

        $.ajax({
            method: 'POST',
            url: '_ajax/SelectModal.ajax.php',
            data: AjaxData,
            dataType: 'json',
            success: function (data) {

                var TableBody;

                $.each(data, function(key, valor) {
                    TableBody += '<tr>';
                    TableBody += "<td><a class='j_select_funcionario_modal' href='"+valor.codigo+"/"+valor.nome+"'>" + valor.codigo + "</a></td>";
                    TableBody += "<td><a class='j_select_funcionario_modal' href='"+valor.codigo+"/"+valor.nome+"'>" + valor.nome + "</a></td>";
                    TableBody += '</tr>';
                });

                if (TableBody) {
                    $('.j_body_table_funcinarios_ajax').html(TableBody);
                }else {
                    $('.j_body_table_funcinarios_ajax').html('Nada Encontrado');
                }

            },
            processData: false,
            cache: false,
            contentType: false
        });
    }

    $('.j_abrir_modal_select_func').click(function() {
        CarregarTableFuncionarioAjax();
        $('.j_modal_select_func').fadeIn();
    });

    $('#j_search_form_funcionario_ajax').submit(function(){
        CarregarTableFuncionarioAjax($('.j_search_input_funcionario_ajax').val());
        return false;
    });

    $(document).on('click', '.j_select_funcionario_modal', function(){
        var Inputs = $(this).attr('href').split('/');
        $('#j_codigo_funcionario').val(Inputs[0]);
        $('#j_value_funcionario').val(Inputs[0]);
        $('#j_nome_funcionario').val(Inputs[1]);
        $('.j_modal_select_func').fadeOut();
        return false;
    });

    // FORNECEDOR MODAL
    function CarregarTableFornecedorAjax(WhereInput){
        var AjaxData = new FormData();
        if (WhereInput) {
            AjaxData.append('WhereInput', WhereInput);
        }
        AjaxData.append('AjaxFile', 'SelectModal');
        AjaxData.append('AjaxAction', 'Fornecedor');

        $.ajax({
            method: 'POST',
            url: '_ajax/SelectModal.ajax.php',
            data: AjaxData,
            dataType: 'json',
            success: function (data) {

                var TableBody;

                $.each(data, function(key, valor) {
                    TableBody += '<tr>';
                    TableBody += "<td><a class='j_select_fornecedor_modal' href='"+valor.codigo+"/"+valor.razaosocial+"/"+valor.cnpj+"'>" + valor.codigo + "</a></td>";
                    TableBody += "<td><a class='j_select_fornecedor_modal' href='"+valor.codigo+"/"+valor.razaosocial+"/"+valor.cnpj+"'>" + valor.razaosocial + "</a></td>";
                    TableBody += '</tr>';
                });

                if (TableBody) {
                    $('.j_body_table_fornecedor_ajax').html(TableBody);
                }else {
                    $('.j_body_table_fornecedor_ajax').html('Nada Encontrado');
                }

            },
            processData: false,
            cache: false,
            contentType: false
        });
    }

    $('.j_abrir_modal_select_fornecedor').click(function() {
        CarregarTableFornecedorAjax();
        $('.j_modal_select_fornecedor').fadeIn();
    });

    $('#j_search_form_fornecedor_ajax').submit(function(){
        CarregarTableFornecedorAjax($('.j_search_input_fornecedor_ajax').val());
        return false;
    });

    $(document).on('click', '.j_select_fornecedor_modal', function(){
        var Inputs = $(this).attr('href').split('/');
        $('#j_codigo_fornecedor').val(Inputs[0]);
        $('#j_value_fornecedor').val(Inputs[2]);
        $('#j_nome_fornecedor').val(Inputs[1]);
        $('.j_modal_select_fornecedor').fadeOut();
        return false;
    });

    // PARCEIRO MODAL
    function CarregarTableParceirosAjax(WhereInput){
        var AjaxData = new FormData();
        if (WhereInput) {
            AjaxData.append('WhereInput', WhereInput);
        }
        AjaxData.append('AjaxFile', 'SelectModal');
        AjaxData.append('AjaxAction', 'Parceiro');

        $.ajax({
            method: 'POST',
            url: '_ajax/SelectModal.ajax.php',
            data: AjaxData,
            dataType: 'json',
            success: function (data) {

                var TableBody;

                $.each(data, function(key, valor) {
                    TableBody += '<tr>';
                    TableBody += "<td><a class='j_select_parceiro_modal' href='"+valor.codigo+"/"+valor.razaosocial+"/"+valor.cnpj+"'>" + valor.codigo + "</a></td>";
                    TableBody += "<td><a class='j_select_parceiro_modal' href='"+valor.codigo+"/"+valor.razaosocial+"/"+valor.cnpj+"'>" + valor.razaosocial + "</a></td>";
                    TableBody += '</tr>';
                });

                if (TableBody) {
                    $('.j_body_table_parceiro_ajax').html(TableBody);
                }else {
                    $('.j_body_table_parceiro_ajax').html('Nada Encontrado');
                }

            },
            processData: false,
            cache: false,
            contentType: false
        });
    }

    $('.j_abrir_modal_select_parceiro').click(function() {
        CarregarTableParceirosAjax();
        $('.j_modal_select_parceiro').fadeIn();
    });

    $('#j_search_form_parceiro_ajax').submit(function(){
        CarregarTableParceirosAjax($('.j_search_input_parceiro_ajax').val());
        return false;
    });

    $(document).on('click', '.j_select_parceiro_modal', function(){
        var Inputs = $(this).attr('href').split('/');
        $('#j_codigo_parceiro').val(Inputs[0]);
        $('#j_value_parceiro').val(Inputs[2]);
        $('#j_nome_parceiro').val(Inputs[1]);
        $('.j_modal_select_parceiro').fadeOut();
        return false;
    });

    // DARF MODAL
    function CarregarTableDarfsAjax(WhereInput){
        var AjaxData = new FormData();
        if (WhereInput) {
            AjaxData.append('WhereInput', WhereInput);
        }
        AjaxData.append('AjaxFile', 'SelectModal');
        AjaxData.append('AjaxAction', 'Darf');

        $.ajax({
            method: 'POST',
            url: '_ajax/SelectModal.ajax.php',
            data: AjaxData,
            dataType: 'json',
            success: function (data) {

                var TableBody;

                $.each(data, function(key, valor) {
                    TableBody += '<tr>';
                    TableBody += "<td><a class='j_select_darf_modal' href='"+valor.codigo+"/"+valor.descricao+"'>" + valor.codigo + "</a></td>";
                    TableBody += "<td><a class='j_select_darf_modal' href='"+valor.codigo+"/"+valor.descricao+"'>" + valor.descricao + "</a></td>";
                    TableBody += '</tr>';
                });

                if (TableBody) {
                    $('.j_body_table_darf_ajax').html(TableBody);
                }else {
                    $('.j_body_table_darf_ajax').html('Nada Encontrado');
                }

            },
            processData: false,
            cache: false,
            contentType: false
        });
    }

    $(document).on('click', '.j_abrir_modal_select_darf', function(){
        CarregarTableDarfsAjax();
        $('.j_modal_select_darf').fadeIn();
    });

    $('#j_search_form_darf_ajax').submit(function(){
        CarregarTableDarfsAjax($('.j_search_input_darf_ajax').val());
        return false;
    });

    $(document).on('click', '.j_select_darf_modal', function(){
        var Inputs = $(this).attr('href').split('/');
        $('#j_codigo_darf').val(Inputs[0]);
        $('#j_value_darf').val(Inputs[0]);
        $('#j_nome_darf').val(Inputs[1]);
        $('.j_modal_select_darf').fadeOut();
        return false;
    });

    // RETENCAO
    $(document).on('change', '.j_select_retencao', function(){
        if ($(this).val() == 1) {
            $('.j_darf').html('<div class="form-group">'+
                '<label>* DARF</label>'+
                '<span class="icon-label cs-pointer j_abrir_modal_select_darf"><i class="fa fa-search"></i></span>'+
                '<div class="row">'+
                    '<div class="col-xs-4">'+
                        '<input class="form-control j_autoc" id="j_value_darf" for="Darf" name="" type="text" value=""/>'+
                    '</div>'+
                    '<div class="col-xs-8">'+
                        '<input class="form-control j_autoc" id="j_nome_darf" for="Darf" name="" type="text" value=""/>'+
                    '</div>'+
                    '<input class="form-control" id="j_codigo_darf" name="codigo_darf" type="hidden" value=""/>'+
                '</div>'+
            '</div>'+
            '<div class="form-group">'+
                '<label>* Data de Vencimento</label>'+
                '<input class="form-control date datepi" type="text" value="" required>'+
            '</div>');

            $('.date').mask('00/00/0000');
            DataPi();
        }else {
            $('.j_darf').html('');
        }
    });

    // GERAR PARCELAS
    function TransformTimeStamp(d){
        if (!d) {
            return '';
        }
        var date = d.split('/');
        var n_date = date[2]+'-'+date[1]+'-'+date[0];
        return(n_date)
    }
    function TransformDate(d){
        if (!d) {
            return '';
        }
        var date = d.split('-');
        var n_date = date[2]+'/'+date[1]+'/'+date[0];
        return(n_date)
    }
    function TransformMoney(v){
        if (!v) {
            return '';
        }
        var valor = v;

        try {
            var valor = valor.toFixed(2);
        } catch (e) {
            // alert(e.message);
        }
        valor = '' + valor;

        valor = valor.replace('.', ',');

        if (valor.indexOf(",") == -1) {
            valor = valor + ',00';
        }

        return(valor)
    }
    function TransformMoneyAmericano(v){
        if (!v) {
            return '';
        }
        var valor = v;

        valor = valor.replace('.', '').replace(',', '.');
        valor = parseFloat(valor).toFixed(2)

        return(valor)
    }

    function AddMonthParcela(d, m){
        // alert(TransformDate(d))
        var DATE = new Date(d);

        var DAY = DATE.getDate();
        var MONTH = DATE.getMonth();
        var YEAR = DATE.getFullYear();

        // DIAS DO MES
        var aux = MONTH+m;
        while (aux >= 12) {
            aux = aux - 12;
        }

        var dm = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
        if ((DAY+1) > dm[aux]) {
            DAY = dm[aux] - 1;
        }
        // /DIA DO MES

        var N_DATE = new Date(YEAR, eval(MONTH+m), DAY);
        var N_MES = N_DATE.getMonth() + 1;
        var N_DAY = N_DATE.getDate() + 1;
        var N_ANO = N_DATE.getFullYear();

        if (N_DAY < 10) {
            N_DAY = '0'+N_DAY;
        }
        if (N_MES < 10) {
            N_MES = '0'+N_MES;
        }

        var RETURN = N_ANO+'-'+N_MES+'-'+N_DAY;

        return(RETURN);
    }

    $('#j_gerar_parcelas').click(function(){
        var QTDD = $('#j_gerar_parcelas_qtd').val();
        var VENC = $('#j_gerar_parcelas_venc').val();
        var VALOR = $('#j_gerar_parcelas_valor').val().replace('.', '').replace('.', '').replace('.', '').replace('.', '').replace('.', '').replace('.', '').replace(',', '.');

        if (!QTDD) {
            alert('Preencha o Campo Quantidade de Parcelas');
            return false;
        }
        if (!VENC) {
            alert('Preencha o Campo Vencimento de Primeira Parcela');
            return false;
        }
        if (!VALOR) {
            alert('Preencha o Campo Valor Total R$');
            return false;
        }

        var ValorParcelas = VALOR/QTDD;
        ValorParcelas = TransformMoney(ValorParcelas);

        var Tbody = '';

        for (var i = 0; i < QTDD; i++) {
            var dataParcela = TransformDate(AddMonthParcela(TransformTimeStamp(VENC), i));

            Tbody += '<tr>'+
                '<td>'+(i+1)+'<input type="hidden" name="Pcodigo[]" value=""></td>'+
                '<td><input class="form-control data datepi" type="text" name="Pdatavencimento[]" value="'+dataParcela+'"></td>'+
                '<td><input class="form-control money2" type="text" name="Pvalor[]" value="'+ValorParcelas+'"></td>'+
                '<td><input class="form-control" type="text" name="Pnum_documento[]" value=""></td>'+
                '<td>-</td>'+
                '<td>À vencer</td>'+
                '<td><input type="hidden" name="Psituacao[]" value=""></td>'+
                '<td></td>'+
            '</tr>';
        }
        $('.j_parcelas_geradas').html(Tbody);

        $('.data').mask('00/00/0000');
        $('.money2').mask("#.##0,00", {reverse: true});
        DataPi();
    });

    // FILTRO
    $('.j_filtro_contas_pagar_select').change(function(){
        if ($(this).val() == 'cs.codigo') {
            var AjaxData = new FormData();
            AjaxData.append('AjaxFile', 'ContasPagar');
            AjaxData.append('AjaxAction', 'ListStatus');

            $.ajax({
                method: 'POST',
                url: '_ajax/ContasPagar.ajax.php',
                data: AjaxData,
                dataType: 'json',
                beforeSend: function () {
                    $('.load_alpha').fadeIn('fast');
                },
                success: function (data) {
                    $('.load_alpha').fadeOut('fast');

                    Select = '';
                    $.each(data, function(key, valor) {
                        Select += "<option value='" + valor.codigo + "'>" + valor.descricao + "</option>";
                    });

                    $('.j_filtro_contas_pagar_input').html('<select class="form-control" name="inputFiltro">'+
                    Select+
                    '</select>');
                },
                error: function(){
                    $('.load_alpha').fadeOut('fast');
                },
                processData: false,
                cache: false,
                contentType: false
            });
        }else if($(this).val() == ''){
            $('.j_filtro_contas_pagar_input').html('');
        }else {
            $('.j_filtro_contas_pagar_input').html('<input class="form-control" type="text" name="inputFiltro" value="">');
        }
    });

    // BAIXA EM TITULO PAGAR
    $('.j_baixa_pagar').click(function(){
        var n_parcela = $(this).attr('for');
        var AjaxData = new FormData();
        AjaxData.append('AjaxFile', 'ContasPagar');
        AjaxData.append('AjaxAction', 'BaixaRead');
        AjaxData.append('Id', $(this).attr('rel'));

        $.ajax({
            method: 'POST',
            url: '_ajax/ContasPagar.ajax.php',
            data: AjaxData,
            dataType: 'json',
            beforeSend: function () {
                $('.load_alpha').fadeIn('fast');
            },
            success: function (data) {
                $('.load_alpha').fadeOut('fast');

                $('#j_baixa_n').val(n_parcela);
                $('#j_baixa_codigo').val(data[0].codigo);
                $('#j_baixa_venc').val(TransformDate(data[0].datavencimento));
                $('#j_baixa_valordepag').val(TransformMoney(data[0].valor));
                $('#j_baixa_banco').val(data[0].codigo_banco);
                $('#j_baixa_tipopag').val(data[0].formapagamento);
                $('#j_baixa_datapag').val(TransformDate(data[0].datapagamento));
                $('#j_baixa_acres').val(TransformMoney(data[0].juros));
                $('#j_baixa_desco').val(TransformMoney(data[0].desconto));
                if (!data[0].valor_pago) {
                    $('#j_baixa_valort').val(TransformMoney(data[0].valor));
                    $('#j_baixa_valort_hidden').val(TransformMoney(data[0].valor));
                }else {
                    $('#j_baixa_valort').val(TransformMoney(data[0].valor_pago));
                    $('#j_baixa_valort_hidden').val(TransformMoney(data[0].valor_pago));
                }

                $('.j_modal_baixa').fadeIn();
            },
            error: function(){
                $('.load_alpha').fadeOut('fast');
                alert('Ocorreu Algum Erro');
            },
            processData: false,
            cache: false,
            contentType: false
        });
    });

    $('.j_baixa_soma_final').change(function(){
        var ValorParcela = TransformMoneyAmericano($('#j_baixa_valordepag').val());
        var acres;
        var desco;

        if (!$('#j_baixa_acres').val()) {
            acres = 0;
        }else {
            acres = TransformMoneyAmericano($('#j_baixa_acres').val());
        }

        if (!$('#j_baixa_desco').val()) {
            desco = 0;
        }else {
            desco = TransformMoneyAmericano($('#j_baixa_desco').val());
        }

        var ValorTotal = (parseFloat(ValorParcela) + parseFloat(acres) - parseFloat(desco));

        $('#j_baixa_valort').val(TransformMoney(ValorTotal));
        $('#j_baixa_valort_hidden').val(TransformMoney(ValorTotal));
    });

    $('.j_form_baixa_pagar').submit(function(){
        var AjaxData = $(this).serialize();
        AjaxData += '&AjaxFile=ContasPagar&AjaxAction=BaixaUp'
        var AjaxForm = $(this);

        if (!ValidaCampos(AjaxForm)) {
            return false;
        }


        $.ajax({
            method: 'POST',
            url: '_ajax/ContasPagar.ajax.php',
            data: AjaxData,
            dataType: 'json',
            beforeSend: function () {
                $('.load_alpha').fadeIn('fast');
            },
            success: function (data) {
                $('.load_alpha').fadeOut('fast');
                $('.j_modal_baixa').fadeOut();

                TriggerError(data.triggerType, data.triggerMsg);
                setTimeout(function(){ location.reload(); }, 2000);
            },
            error: function(){
                $('.load_alpha').fadeOut('fast');
            }
        });
        return false;
    });


    // MODAL RECUPERA PARCELA PAGAR
    $('.j_recupera_parcela_pagar').click(function(){
        $('.j_recupera_yes_pagar').attr('rel', $(this).attr('rel'));
        $('.j_modal_recupera_parcela_pagar').fadeIn();
        return false;
    });

    $('.j_recupera_no_pagar').click(function(){
        $('.j_modal_recupera_parcela_pagar').fadeOut();
        return false;
    });

    $('.j_recupera_yes_pagar').click(function(){
        $('.j_modal_recupera_parcela_pagar').fadeOut();
        var Id = $(this).attr('rel');
        var AjaxData = 'AjaxFile=ContasPagar&AjaxAction=RecuperaParcela&Id='+Id;
        var AjaxForm = $(this);

        $.ajax({
            method: 'POST',
            url: '_ajax/ContasPagar.ajax.php',
            data: AjaxData,
            dataType: 'json',
            beforeSend: function () {
                $('.load_alpha').fadeIn('fast');
            },
            success: function (data) {
                $('.load_alpha').fadeOut('fast');
                $('.j_modal_baixa').fadeOut();

                TriggerError(data.triggerType, data.triggerMsg);
                setTimeout(function(){ location.reload(); }, 2000);
            },
            error: function(){
                $('.load_alpha').fadeOut('fast');
            }
        });
        $('.j_modal_recupera_parcela').fadeOut();
        return false;
    });

    // PRORROGACAO
    $(document).on('click', '.j_delete_prorrogacao', function(){
        var del = 'P'+$(this).attr('rel');
        $('#'+del).remove();
        $(this).remove();
    });

    $('.j_add_prorrogacao').click(function(){
        var TR = $(this).parent().parent();

        var CodigoParcela = TR.find('input[name="Pcodigo[]"]').val();

        if ($('td').hasClass('prInput')) {
            TR.find('.prInput').html('<input class="form-control date datepi" id="P'+CodigoParcela+'" type="text" name="PRORROGACAO['+CodigoParcela+']" value="">');
            TR.find('.prDel').html('<i class=" fa fa-trash cs-pointer j_delete_prorrogacao" rel="'+CodigoParcela+'"></i>');
        }else{
            $('.j_th_prorrogacao').after('<th class="al_center">Prorrogacao</th><th></th>');
            $('.j_td_prorrogacao').after('<td class="prInput"></td>'+
            '<td class="prDel"></td>');

            TR.find('.prInput').html('<input class="form-control date datepi" id="P'+CodigoParcela+'" type="text" name="PRORROGACAO['+CodigoParcela+']" value="">');
            TR.find('.prDel').html('<i class=" fa fa-trash cs-pointer j_delete_prorrogacao" rel="'+CodigoParcela+'"></i>');
        }

        $('.date').mask('00/00/0000');
        $('.money2').mask("#.##0,00", {reverse: true});
        DataPi();
        // $(this).remove();
    });

    /***************************************
    ########## PAG CONTAS RECEBER ##########
    ****************************************/
    // FILTRO
    $('.j_filtro_contas_receber_select').change(function(){
        if($(this).val() == ''){
            $('.j_filtro_contas_receber_input').html('');
        }else if($(this).val() == 'cli.cnpj'){
            $('.j_filtro_contas_receber_input').html('<input class="form-control cnpj" type="text" name="inputFiltro" value="">');
            $('.cnpj').mask('00.000.000/0000-00', {reverse: true});
        }else{
            $('.j_filtro_contas_receber_input').html('<input class="form-control" type="text" name="inputFiltro" value="">');
        }
    });

    // MODAL RECUPERA PARCELA RECEBER
    $('.j_recupera_parcela_receber').click(function(){
        $('.j_recupera_yes_receber').attr('rel', $(this).attr('rel'));
        $('.j_modal_recupera_parcela_receber').fadeIn();
        return false;
    });

    $('.j_recupera_no_receber').click(function(){
        $('.j_modal_recupera_parcela_receber').fadeOut();
        return false;
    });

    $('.j_recupera_yes_receber').click(function(){
        $('.j_modal_recupera_parcela_receber').fadeOut();
        var Id = $(this).attr('rel');
        var AjaxData = 'AjaxFile=ContasReceber&AjaxAction=RecuperaParcela&Id='+Id;
        var AjaxForm = $(this);

        $.ajax({
            method: 'POST',
            url: '_ajax/ContasReceber.ajax.php',
            data: AjaxData,
            dataType: 'json',
            beforeSend: function () {
                $('.load_alpha').fadeIn('fast');
            },
            success: function (data) {
                $('.load_alpha').fadeOut('fast');
                $('.j_modal_baixa').fadeOut();

                TriggerError(data.triggerType, data.triggerMsg);
                setTimeout(function(){ location.reload(); }, 2000);
            },
            error: function(){
                $('.load_alpha').fadeOut('fast');
            }
        });
        $('.j_modal_recupera_parcela_receber').fadeOut();
        return false;
    });

    // BAIXA EM TITULO RECEBER
    $('.j_baixa_receber').click(function(){
        var n_parcela = $(this).attr('for');
        var AjaxData = new FormData();
        AjaxData.append('AjaxFile', 'ContasReceber');
        AjaxData.append('AjaxAction', 'BaixaRead');
        AjaxData.append('Id', $(this).attr('rel'));

        $.ajax({
            method: 'POST',
            url: '_ajax/ContasReceber.ajax.php',
            data: AjaxData,
            dataType: 'json',
            beforeSend: function () {
                $('.load_alpha').fadeIn('fast');
            },
            success: function (data) {
                $('.load_alpha').fadeOut('fast');

                $('#j_baixa_n').val(n_parcela);
                $('#j_baixa_codigo').val(data[0].codigo);
                $('#j_baixa_venc').val(TransformDate(data[0].datavencimento));
                $('#j_baixa_valordepag').val(TransformMoney(data[0].valor));
                $('#j_baixa_banco').val(data[0].codigo_banco);
                $('#j_baixa_tipopag').val(data[0].formapagamento);
                $('#j_baixa_datapag').val(TransformDate(data[0].datapagamento));
                $('#j_baixa_acres').val(TransformMoney(data[0].juros));
                $('#j_baixa_desco').val(TransformMoney(data[0].desconto));
                if (!data[0].valor_pago) {
                    $('#j_baixa_valort').val(TransformMoney(data[0].valor));
                    $('#j_baixa_valort_hidden').val(TransformMoney(data[0].valor));
                }else {
                    $('#j_baixa_valort').val(TransformMoney(data[0].valor_pago));
                    $('#j_baixa_valort_hidden').val(TransformMoney(data[0].valor_pago));
                }

                $('.j_modal_baixa').fadeIn();
            },
            error: function(){
                $('.load_alpha').fadeOut('fast');
                alert('Ocorreu Algum Erro');
            },
            processData: false,
            cache: false,
            contentType: false
        });
    });

    $('.j_form_baixa_receber').submit(function(){
        var AjaxData = $(this).serialize();
        AjaxData += '&AjaxFile=ContasReceber&AjaxAction=BaixaUp'
        var AjaxForm = $(this);

        if (!ValidaCampos(AjaxForm)) {
            return false;
        }

        $.ajax({
            method: 'POST',
            url: '_ajax/ContasReceber.ajax.php',
            data: AjaxData,
            dataType: 'json',
            beforeSend: function () {
                $('.load_alpha').fadeIn('fast');
            },
            success: function (data) {
                $('.load_alpha').fadeOut('fast');
                $('.j_modal_baixa').fadeOut();

                TriggerError(data.triggerType, data.triggerMsg);
                setTimeout(function(){ location.reload(); }, 2000);
            },
            error: function(){
                $('.load_alpha').fadeOut('fast');
            }
        });
        return false;
    });

    // PARCEIRO CLIENTE
    function CarregarTableClienteAjax(WhereInput){
        var AjaxData = new FormData();
        if (WhereInput) {
            AjaxData.append('WhereInput', WhereInput);
        }
        AjaxData.append('AjaxFile', 'SelectModal');
        AjaxData.append('AjaxAction', 'Cliente');

        $.ajax({
            method: 'POST',
            url: '_ajax/SelectModal.ajax.php',
            data: AjaxData,
            dataType: 'json',
            success: function (data) {

                var TableBody;

                $.each(data, function(key, valor) {
                    TableBody += '<tr>';
                    TableBody += "<td><a class='j_select_cliente_modal' href='"+valor.codigo+"/"+valor.razaosocial+"/"+valor.cnpj+"'>" + valor.codigo + "</a></td>";
                    TableBody += "<td><a class='j_select_cliente_modal' href='"+valor.codigo+"/"+valor.razaosocial+"/"+valor.cnpj+"'>" + valor.razaosocial + "</a></td>";
                    TableBody += '</tr>';
                });

                if (TableBody) {
                    $('.j_body_table_cliente_ajax').html(TableBody);
                }else {
                    $('.j_body_table_cliente_ajax').html('Nada Encontrado');
                }

            },
            processData: false,
            cache: false,
            contentType: false
        });
    }

    $('.j_abrir_modal_select_cliente').click(function() {
        CarregarTableClienteAjax();
        $('.j_modal_select_cliente').fadeIn();
    });

    $('#j_search_form_cliente_ajax').submit(function(){
        CarregarTableClienteAjax($('.j_search_input_cliente_ajax').val());
        return false;
    });

    $(document).on('click', '.j_select_cliente_modal', function(){
        var Inputs = $(this).attr('href').split('/');
        $('#j_codigo_cliente').val(Inputs[0]);
        $('#j_value_cliente').val(Inputs[2]);
        $('#j_nome_cliente').val(Inputs[1]);
        $('.j_modal_select_cliente').fadeOut();
        return false;
    });

    // SELECT CLIENTE
    function AttProjetos(){
        var IdCliente = $('#j_codigo_cliente').val();
        var AjaxData = 'Id='+IdCliente+'&AjaxFile=ContasReceber&AjaxAction=SelectCliente';

        if ($(".j_Projetos").length) {
            $.ajax({
                method: 'post',
                url: '_ajax/ContasReceber.ajax.php',
                data: AjaxData,
                dataType: 'json',
                beforeSend: function () {
                    $('.load_alpha').fadeIn('fast');
                },
                success: function (data) {
                    $('.load_alpha').fadeOut('fast');

                    if(!data.empty){
                        var options="<option value=''> Selecione... </option>";
                        $(".j_Projetos").html(options);

                        $.each(data, function(key, valor) {
                            options += "<option value='" + valor.codigo + "'>" + valor.projeto + "</option>";
                        });
                        $(".j_Projetos").html(options);
                    }else{
                        var options="<option disabled> Selecione... </option>";
                        $(".j_Projetos").html(options);
                    }
                },
                error: function(){
                    $('.load_alpha').fadeOut('fast');
                },
            });
        }
    }

    $('.j_select_cliente_contas').change(function(){
        // AttProjetos();
    });

    // PRIMEIRA DATA DE VENCIMENTO
    $('.j_first_vencimento').change(function(){
        var Data = $(this).val();
        $('.j_first_vencimento').val(Data);
    });

    // CONFERE A INTEGRIDADE DAS PARCELAS
    $('.j_integridade_das_parcelas').click(function(){
        var InputValor = $('input[name="Pvalor[]"]');
        var Soma = 0;


        $.each(InputValor, function() {
            Soma += parseFloat(TransformMoneyAmericano($(this).val()));
        });

        if (Soma == 0) {
            var msgT = '<b>MSG: </b> Clique no botao Gerar Parcelas';
            var typeT = 'trigger_alert';
            TriggerError(typeT, msgT);

            return false;
        }

        if (Soma != parseFloat(TransformMoneyAmericano($('#j_gerar_parcelas_valor').val()))) {
            var msgT = '<b>MSG: </b> Valor total diferente do valor das Parcelas';
            var typeT = 'trigger_alert';
            TriggerError(typeT, msgT);
        }else {
            $('.j_submeter_j_form').click();
        }

        return false;
    });
    /***************************************
    ################ PAG FC ################
    ****************************************/
    $('#j_gera_fc_pdf').click(function(){
        $('#HtmlPDF').val($('#ExportPDF').html());
        $('#PrintFC').val('');
        $('#EmailPF').val('');
        $('#FormPDF').submit();
        return false;
    })
    $('#j_print_fc').click(function(){
        $('#PrintFC').val($('#ExportPDF').html());
        $('#HtmlPDF').val('');
        $('#EmailPF').val('');
        $('#FormPDF').submit();
        return false;
    })
    $('#j_fc_email').click(function(){
        $('.j_modal_fc_email').fadeIn();
        return false;
    })
    $('.j_cancelar_email_fc').click(function(){
        $('.j_modal_fc_email').fadeOut();
        return false;
    });

    $('.j_enviar_email_fc').click(function(){
        var Emails = $('.textarea-email').val();
        var HTMl = $('#ExportPDF').html();

        if (!Emails) {
            alert('Preencha o Campo de Email');
            return false;
        }

        // return false;

        var AjaxData = new FormData();
        AjaxData.append('email', Emails);
        AjaxData.append('html', HTMl);

        $.ajax({
            method: 'POST',
            url: '_ajax/Email-FC.ajax.php',
            data: AjaxData,
            dataType: 'json',
            beforeSend: function () {
                $('.load_alpha').fadeIn('fast');
            },
            success: function (data) {
                $('.load_alpha').fadeOut('fast');

                TriggerError(data.triggerType, data.triggerMsg);

                if (data.emailinvalido) {
                    $('.j_modal_fc_email').fadeIn();
                }else {
                    $('.textarea-email').val('');
                }
            },
            error: function(){
                $('.load_alpha').fadeOut('fast');
                TriggerError('trigger_error', '<b>Erro</b>');
            },
            processData: false,
            cache: false,
            contentType: false
        });

        $('.j_modal_fc_email').fadeOut();
        return false;
    })

    /***************************************
    ########### PAG RELATORIO CP ###########
    ****************************************/
    $('.j_filtro_contas_pagar_rel_select').change(function(){
        if($(this).val() == ''){
            $('.j_filtro_contas_pagar_rel_input').html('');
        }else {
            $('.j_filtro_contas_pagar_rel_input').html('<input class="form-control" type="text" name="inputFiltro" value="">');
        }
    });

    /***************************************
    ########### PAG RELATORIO ##############
    ****************************************/
	$('.j_submit_report').on('click', function() {
		if ($("input[name='codigo_unimed']").val().length == 0) {
			TriggerError('trigger_error', 'Obrigatório selecionar uma singular');
			return false;
		}
		$(this).onsubmit();
	});

	$('.j_select_all').on('click', function() {
		$('.j_codigo_unimed').select2('destroy').find('option').prop('selected', ($(this).is(':checked') ? 'selected' : false)).end().select2();
	});

    $('.j_select_all_service').on('click', function() {
		$('.j_service_unimed').select2('destroy').find('option').prop('selected', ($(this).is(':checked') ? 'selected' : false)).end().select2();
	});

	// $('.j_codigo_unimed').on('change', function() {
	// 	$("input[type='text']").val('');
	// 	//$('.j_select_all').prop('checked', false);
	// 	$("select[name='codigo_status']").val('ERRO');
    //
	// 	if ($("input[name='module']").val() == 'transaction') {
	// 		var itwvDATA = new Date();
	// 		var itwvPDAT = new Date(itwvDATA.getFullYear(), itwvDATA.getMonth(), 1);
	// 		var itwvUDAT = new Date(itwvDATA.getFullYear(), itwvDATA.getMonth() + 1, 0);
    //
	// 		itwvANO  = itwvPDAT.getFullYear();
	// 		itwvMES  = (itwvPDAT.getMonth() + 1);
	// 		itwvDIAP = itwvPDAT.getDay();
	// 		itwvDIAU = itwvUDAT.getDate()
    //
	// 		$("input[name='data_inicial']").val(itwvSTRPAD(itwvDIAP, 2) + '/' + itwvSTRPAD(itwvMES, 2) + '/' + itwvANO);
	// 		$("input[name='data_final']").val(itwvSTRPAD(itwvDIAU, 2) + '/' + itwvSTRPAD(itwvMES, 2) + '/' + itwvANO);
	// 	}
	// });

    /***************************************
    ########### EXPORT REPORT ##############
    ****************************************/
    var btnAllClicked = false;
    $('.itwvExportReportAll').click(function(){
        if ($('#itwvALL').val() != 'yes') {
            $('#itwvALL').val('yes');
            btnAllClicked = true;
        }
        $('.itwvExportReport').click();
    });

    $('.itwvExportReport').click(function(){
		var AjaxData  = new FormData();
		var itwvTYPE  = $(this).attr('for');
		if (itwvTYPE == 'xls')
			var itwvFILE = 'Export_XLS.ajax.php';
		else if (itwvTYPE == 'xlsx')
			var itwvFILE = 'Export_XLSX.ajax.php';
		else
			var itwvFILE = 'Export_CSV.ajax.php';

		$('.j_form_report').find('input,select').each(function() {
			AjaxData.append($(this).attr('name'), $(this).val());
		});

        $.ajax({
            method: 'POST',
            url: '../../../_ajax/'+ itwvFILE,
            data: AjaxData,
            dataType: 'json',
            beforeSend: function () {
                $('.load_alpha').fadeIn('fast');
            },
            success: function (itwvRETURN) {
				$('.load_alpha').fadeOut('fast');
				TriggerError(itwvRETURN.triggerType, itwvRETURN.triggerMsg);

				if (itwvRETURN.codigo == 1) {
					var itwvURL = '../../../_ajax/ForceDownload.ajax.php?itwvPATH='+ itwvRETURN.path +'&file=';
					window.open(itwvRETURN.url + itwvURL + itwvRETURN.file);
				}
            },
            error: function(itwvRETURN){
                $('.load_alpha').fadeOut('fast');
                TriggerError('trigger_error', '<b>ERRO FATAL!</b><hr />'+ itwvRETURN.responseText);
            },
            processData: false,
            cache: false,
            contentType: false
        });

        if (btnAllClicked) {
            btnAllClicked = false;
            $('#itwvALL').val('');
        }
    });

    // AUTO COMPLETE -----------------------------------------------------------------------------------------------------------------------------------------------------------
    var IdAtvCampo;
    var CodigoClienteCampo;
    var ClienteCampo;
    var ProjetoCampo;
    var ModuloCampo;
    var AtividadeCampo;
    var HorasCampo;

    function ParentTR(This){
        IdAtvCampo = This.parent().parent().find('.j_codigo_atividade_projeto');
        CodigoClienteCampo = This.parent().parent().find('.j_codigo_cliente');
        ClienteCampo = This.parent().parent().find('.j_nome_cliente');
        ProjetoCampo = This.parent().parent().find('.j_Projetos');
        ModuloCampo = This.parent().parent().find('.j_modulo');
        AtividadeCampo = This.parent().parent().find('.j_atividade');
        HorasCampo = This.parent().parent().find('.j_hora_estimada');
    }

    $(document).on("focus", '.j_autoc_cliente_ativ', function(){
        ParentTR($(this));

        SelectClienteATV($(this));
    });

    function SelectClienteATV(This){
        This.autocomplete({
            source: function(req, response) {
                $.ajax({
                    method: "POST",
                    url: '_ajax/AutoCCliente.ajax.php',
                    dataType: "json",
                    data: { term: $.trim(This.val()) },
                    success: function( data ) {
                        var re = $.ui.autocomplete.escapeRegex(req.term);
                        var matcher = new RegExp( "^" + re, "i" );
                        response($.grep(data, function(item) {
                            if (matcher.test(item.value) == false) {
                                return { id: 0, label: "Nenhum registro encontrado!" };
                            } else{
                                return matcher.test(item.value);
                            }
                        }));
                    }
                });
            },
            minLength: 1,
            select: function (event, itwvJSON) {
                if (itwvJSON.item.id != 0) {
                    CodigoClienteCampo.val(itwvJSON.item.codigo);
                    LabelCliente = itwvJSON.item.label;

                    AutocThis = This;
                } else
                return false;

                ModuloCampo.html('<option disabled>Selecione um Projeto...</option>');
                AtividadeCampo.html('<option disabled>Selecione um Modulo...</option>');
                IdAtvCampo.val('');
                HorasCampo.val('');

                AttProjetosSelectID();
            }
        });
    }

    function AttProjetosSelectID(){
        var IdCliente = CodigoClienteCampo.val();
        var AjaxData = 'Id='+IdCliente+'&AjaxFile=ContasReceber&AjaxAction=SelectCliente';

        if ($(".j_Projetos").length) {
            $.ajax({
                method: 'post',
                url: '_ajax/ContasReceber.ajax.php',
                data: AjaxData,
                dataType: 'json',
                beforeSend: function () {
                    $('.load_alpha').fadeIn('fast');
                },
                success: function (data) {
                    $('.load_alpha').fadeOut('fast');

                    if(!data.empty){
                        var options="<option value=''> Selecione... </option>";
                        ProjetoCampo.html(options);

                        $.each(data, function(key, valor) {
                            options += "<option value='" + valor.codigo + "'>" + valor.projeto + "</option>";
                        });
                        ProjetoCampo.html(options);
                    }else{
                        var options="<option disabled> Selecione... </option>";
                        ProjetoCampo.html(options);
                    }
                },
                error: function(){
                    $('.load_alpha').fadeOut('fast');
                },
            });
        }
    }

    $(document).on('change', '.j_select_modulo_atv', function(){
        ParentTR($(this));

        ModuloCampo.html('<option disabled>Selecione um Projeto...</option>');
        AtividadeCampo.html('<option disabled>Selecione um Modulo...</option>');
        IdAtvCampo.val('');
        HorasCampo.val('');

        var IdProjeto = $(this).val();
        var AjaxData = 'Id='+IdProjeto+'&AjaxFile=Atividades&AjaxAction=SelectModulo';

        $.ajax({
            method: 'post',
            url: '_ajax/Atividades.ajax.php',
            data: AjaxData,
            dataType: 'json',
            beforeSend: function () {
                $('.load_alpha').fadeIn('fast');
            },
            success: function (data) {
                $('.load_alpha').fadeOut('fast');

                if(!data.empty){
                    var options="<option value=''> Selecione... </option>";
                    ModuloCampo.html(options);

                    $.each(data, function(key, valor) {
                        options += "<option value='" + valor.modulo + "'>" + valor.modulo + "</option>";
                    });
                    ModuloCampo.html(options);
                }else{
                    var options="<option disabled> Selecione... </option>";
                    ModuloCampo.html(options);
                }
            },
            error: function(){
                $('.load_alpha').fadeOut('fast');
            },
        });
    })

    $(document).on('change', '.j_select_atividade_atv', function(){
        ParentTR($(this));

        AtividadeCampo.html('<option disabled>Selecione um Modulo...</option>');
        IdAtvCampo.val('');
        HorasCampo.val('');

        var AjaxData = 'Id='+ProjetoCampo.val()+'&Modulo='+$(this).val()+'&AjaxFile=Atividades&AjaxAction=SelectAtividade';

        $.ajax({
            method: 'post',
            url: '_ajax/Atividades.ajax.php',
            data: AjaxData,
            dataType: 'json',
            beforeSend: function () {
                $('.load_alpha').fadeIn('fast');
            },
            success: function (data) {
                $('.load_alpha').fadeOut('fast');

                if(!data.empty){
                    var options="<option value=''> Selecione... </option>";
                    AtividadeCampo.html(options);

                    $.each(data, function(key, valor) {
                        options += "<option value='" + valor.codigo + "'>" + valor.atividade + "</option>";
                    });
                    AtividadeCampo.html(options);
                }else{
                    var options="<option disabled> Selecione... </option>";
                    AtividadeCampo.html(options);
                }
            },
            error: function(){
                $('.load_alpha').fadeOut('fast');
            },
        });
    })

    $(document).on('change', '.j_estimativa', function(){
        ParentTR($(this));

        IdAtvCampo.val('');
        HorasCampo.val('');

        var AjaxData = 'Id='+$(this).val()+'&AjaxFile=Atividades&AjaxAction=SelectEstimativa';

        $.ajax({
            method: 'post',
            url: '_ajax/Atividades.ajax.php',
            data: AjaxData,
            dataType: 'json',
            beforeSend: function () {
                $('.load_alpha').fadeIn('fast');
            },
            success: function (data) {
                $('.load_alpha').fadeOut('fast');

                if(!data.empty){
                    IdAtvCampo.val(data[0].codigo);
                    HorasCampo.val(data[0].hora_estimada);
                }
            },
            error: function(){
                $('.load_alpha').fadeOut('fast');
            },
        });
    });

    $('.j_select_search_usuario').change(function(){
        let txt_busca = $(this).val();

        if(  txt_busca== 'nascimento'){
            $('input[name="search"]').fadeOut('fast');
            $('select[name="ativo"]').fadeOut('fast');
            $('select[name="mes_aniversario"]').fadeIn().attr("required", "required");


             console.log(txt_busca);
        }else{
            $('input[name="search"]').fadeIn('fast');
            $('select[name="mes_aniversario"]').fadeOut().removeAttr("required");
        }


        if( txt_busca == 'ativo' ){
            $('input[name="search"]').fadeOut('fast');
            $('select[name="ativo"]').fadeIn() ;
        }

        if(  txt_busca === 'nome' || txt_busca === 'email'  ){
            $('input[name="mes_aniversario"]').fadeOut();
            $('select[name="ativo"]').fadeOut('fast');
        }
        //console.log(  $(this).val() );
    });

    $('#something').click(function() {
        var AjaxData  = new FormData();
		$('#itwvALL').val('yes');

		$('.j_form_report').submit();
        return false;
    });
});
