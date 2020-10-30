$(document).ready(function () {

    $(".btn-toggle-form").click(function(e){
        e.preventDefault();
        var btn = $(this);

        var form_id = btn.parents("form").attr("id");
        var btn_data_form_token  = btn.parents("form").find("[name='TOKEN']").val()+"|"+btn.parents("form").find("[name='TIPO_FORM']").val();
        var form_token = btn_data_form_token.split("|");

        var new_form_token = btn.data("token").split("|");
        var new_form_id = btn.data("formid");

        btn.data("token", btn_data_form_token);
        btn.data("formid", form_id);

        btn.parents("form").attr("action", "javascript:sendForm('"+new_form_id+"')");
        btn.parents("form").attr("id") == form_id ? btn.parents("form").attr("id",new_form_id) : btn.parents("form").attr("id",form_id);
        btn.parents("form").attr("name") == form_id ? btn.parents("form").attr("name",new_form_id) : btn.parents("form").attr("name",form_id);
        
        btn.parents("form").find("[name='TOKEN']").val() == form_token[0] ? btn.parents("form").find("[name='TOKEN']").val(new_form_token[0]) : btn.parents("form").find("[name='TOKEN']").val(form_token[0]);
        btn.parents("form").find("[name='TIPO_FORM']").val() == form_token[1] ? btn.parents("form").find("[name='TIPO_FORM']").val(new_form_token[1]) : btn.parents("form").find("[name='TOKEN']").val(form_token[1]);

        btn.toggleClass("active");
        btn.find("i").toggleClass("fa-angle-double-right fa-angle-double-left");
    });

    //Chamada do Placeholder
    $('input, textarea').placeholder();

    $('.btn_privacidade').click(function(){
        var iframe=$('#privacidade').find('iframe');

        if(iframe.attr('src')==undefined){
            var link=iframe.attr('data-src');
            iframe.attr('src',link);
        }

        $('#privacidade').modal();
    });

    $('.modal-closet').click(function(){
        $('.form_response').fadeOut();
    });

    $('select[name=ESTADO]').change(function(event) {

        var e = $(this);
        var value = e.val();

        var select_name = "CIDADE";

        var el = e.parents('form').find('select[name='+select_name+']');
        el.html('<option value="">Carregando...</option>');

        $.ajax({
            url: ROOT + 'cidades/' + value,     
            dataType: 'json',       
        })
        .done(function(data) {

            var html = "<option value=''>Cidade</option>";
            
            $.each(data, function(index, val) {
                 html += "<option>" + val.cidade + "</option>";
            });

            el.html(html)
        });

    });

    $('input[name=TOKEN]').each(function(event) {

        var e = $(this);
        var value = e.val();

        var el = e.parents('form').find('select[name=FK_EMPRESAS_ID]');
        el.html('<option value="">Carregando...</option>');

        $.ajax({
            url: ROOT + 'empresas/' + value,     
            dataType: 'json',       
        })
        .done(function(data) {

            var html = "<option value=''>Escolha uma unidade</option>";
            
            $.each(data, function(index, val) {
                 html += "<option value='"+val.ID+"'>" + val.NOME + "</option>";
            });

            el.html(html)
        });

    });

    $('select[name=FK_EMPRESAS_ID]').change(function(event) {

        var e = $(this);
        var value = e.val();

        var el = e.parents('form').find('select[name=FK_MODELOS_ID]');
        el.html('<option value="">Carregando...</option>');

        $.ajax({
            url: ROOT + 'modelos/' + value,     
            dataType: 'html',       
        })
        .done(function(data) {
            el.html(data)
        });

    });

    mascaras();

    $.getScript("//rel.leadforce.com.br/assets/plugins/ht/ht.js");
    $.getScript("//www.googleadservices.com/pagead/conversion_async.js");
});

function GeraConversao(formulario){
    window.dataLayer = window.dataLayer || []

    var form = $('form[name=' + formulario + ']');
    var midia = form.find('input[name=FK_MIDIAS_ID]').val().trim();

    if(midia == 3 || midia == 37 || midia == 38) {
        dataLayer.push({
           'event': 'sucessoForm',
        });
    }

    if (typeof(ga) !== 'undefined') {
        ga('send', 'event', 'formulario', 'clique', formulario);
    }

}

function mascaras() {

    $("[mascara=numeros]").on("keyup", function () {
        $(this).val($(this).val().replace(/\D/g, ''));
    });

    $("[mascara=letras]").on("keyup", function () {
        $(this).val($(this).val().replace(/\d/g, ''));
    });

    $("[mascara=telefone]").on("focus", function () {
        $(this).mask('(99)99999-9999');
    });

    $("[mascara=data]").on("focus", function () {
        $(this).mask('99/99/9999');
    });

    $("[mascara=cep]").on("focus", function () {
        $(this).mask('99999-999');
    });

    $("[mascara=cpf]").on("focus", function () {
        $(this).mask('999.999.999-99');
    });

    //CEP
    $("[mascara=cnpj]").on("focus", function () {
        $(this).mask('99.999.999/9999-99');
    });

    $("[mascara=cpf_cnpj]").keydown(function(){
        try {
            $(this).unmask();
        } catch (e) {}

        var tamanho = $(this).val().length;

        if(tamanho < 11){
            $(this).mask("999.999.999-99");
        } else {
            $(this).mask("99.999.999/9999-99");
        }
    });

    $("[mascara=cartao]").on("focus", function () {
        $(this).mask('9999-9999-9999-9999');
    });
    
    $("[mascara=ano]").on("focus", function () {
        $(this).mask('9999');
    });

    $("[mascara=moeda]").on("focus", function () {
        $(this).mask('000.000.000,00', {
            reverse: true
        });
    });

    $("[mascara=moeda_sem_centavos]").on("focus", function () {
        $(this).mask('000.000.000', {
            reverse: true
        });
    });

    $("[mascara=data_hora]").on("focus", function () {
        $(this).mask('99/99/9999 99:99');
    });

}

function getSuccessMsgForm(primeiro_nome, msg_success){
    msg_success.find(".nome").text(primeiro_nome);
    msg_success.fadeIn();
}

function setErroForm(objError, formulario){

    $("input, select, textarea").css("border", "");
    $("input[type=checkbox]").css("outline", "");
    $("input[type=radio]").css("outline", "");
    var arrKeys = Object.keys(objError);
    for (i = 0; i < arrKeys.length; i++) {

        $("#"+formulario+" input[name='"+arrKeys[i]+"']").css("border", "1px solid red");
        $("#"+formulario+" input[type=checkbox][name='"+arrKeys[i]+"']").css("outline", "1px solid red");
        $("#"+formulario+" input[type=radio][name='"+arrKeys[i]+"']").css("outline", "1px solid red");
        $("#"+formulario+" select[name='"+arrKeys[i]+"']").css("border", "1px solid red");
        $("#"+formulario+" textarea[name='"+arrKeys[i]+"']").css("border", "1px solid red");
    }
}

function sendForm(formulario){

    var form = $('form[name='+formulario+']');
    var action = form.data('action');
    
    var load = form.find(".load_form");
    var msg_success = form.find(".form_response");

    var nome = form.find("input[name='NOME']").val().trim();
    var primeiro_nome = nome.split(' ')[0];
    
    form.find("button").attr("disabled", true);

    load.fadeIn(function(){
        $.ajax({
            type: "POST",
            url: action,
            async:true,
            data: form.serialize(),
            dataType: 'json',
            success: function(response) {

                GeraConversao(form.attr('name'));
                
                getSuccessMsgForm(primeiro_nome, msg_success);
                $('form[name='+formulario+']')[0].reset();

                $("input, select, textarea").css("border", "");
                $("input[type=checkbox], input[type=radio]").css("outline", "");

                setTimeout(function() {
                    $('.form_response').fadeOut();
                }, 5000);

            },
            error: function(response){

                if(response.responseJSON.descricao){

                    setErroForm(response.responseJSON.descricao, formulario);

                }else{

                    alert("No momentos nossos servidores estão ocupados.");

                }

            },
            complete: function(){                
                load.fadeOut();
                form.find("button").attr("disabled", false);
            }
        });
    });
}