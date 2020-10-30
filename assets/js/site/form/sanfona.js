$('.acordion').click(function () {
    var id = $(this).data('acordion');
    var obj = $('#' + id);

    if ($(obj).is(':visible') == false) {

        $('#form .collapse').slideUp('fast');
        $(obj).slideDown('fast');
    }
});

//mostra/escode form no footer
if($(window).width() > 1050){
    var form = $(".background");
    var height = 880; //Se quiser fazer o somatório de dois lugares, faça porém acumule nessa variavel em cache
    $(window).scroll(function () {
        if ($(this).scrollTop() > ($("body").outerHeight() - height)) {
            form.fadeOut();
        } else {
            form.fadeIn();
        }
    });
}

/* subir form para o topo */
var offset = 50;
var $meuForm = $('.form_container');

if ($(window).width() > 1100) {
    $(document).on('scroll', function () {

        if (offset < $(window).scrollTop()) {
            $meuForm.addClass('form-topo');
        } else {
            $meuForm.removeClass('form-topo');
        }
    });
};
/* subir form para o topo */