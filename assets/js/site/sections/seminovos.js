$(document).ready(function() {
    owl_sync($('.owl_banner_seminovos'), $('.owl_banner_seminovos_bottom'), true, false, false, false, 2, 4, 5);
    
    $("[data-fancybox]").fancybox();

    //adicionar versões no form de cotação pelo botão na descrição das versões
    $(".btn-add-versao").click(function() {
        var index = $(this).data("versao-index");

        $(this).find("i").toggleClass("fa-check");

        $(".bootstrap-select .dropdown-menu [data-original-index='" + index + "'] a").trigger("click");
    });

    $('.form_orcamento .selectpicker').change(function() {
        $('.dropdown-menu').removeClass('show');
        $('[data-versao-index]').find('i').removeClass("fa-check");

        $(".form_orcamento .selectpicker option:selected").each(function() {      
            $(".btn-add-versao[data-versao-index=" + $(this).index() + "] i").toggleClass("fa-check");
        });
    });
});

//adicionar versões no form de cotação pelo botão na descrição das versões
$(".btn-add-toform").click(function(e) {
    e.preventDefault();

    var index = $(this).data("select-index");

    if (!$(this).hasClass('active')) {
        if ($(window).width() < 1024) {
            $("html, body").stop().animate({scrollTop:$("#form").offset().top}, 500, 'swing', function() {});
        }else {
            if($(this).hasClass('btn-versoes')){
                $("html, body").stop().animate({scrollTop:$("#versoes-novos").offset().top - 130}, 500, 'swing', function() {});
            }
        }
    }

    $(".bootstrap-select [data-original-index='" + index + "'] a").trigger("click");
});
