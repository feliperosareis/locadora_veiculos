$(document).ready(function () {
    //EXEMPLO DE CHAMADA DO DATETIMEPICKER
    //Não carrego, pois ele não vem como padrão no groupsConfig.php
    /*
     if (jQuery().datetimepicker) {
     $('[data=time]').datetimepicker({
     lang: 'pt-BR',
     i18n: {
     pt: {
     months: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
     dayOfWeek: ['D', 'S', 'T', 'Q', 'Q', 'S', 'S', 'D']
     }
     },
     format: 'd/m/Y H:i',
     closeOnDateSelect: true
     });
     }
     */

     owl_sync($('.owl_banner_seminovos'), $('.owl_banner_seminovos_bottom'), true, false, false, false, 2, 4, 5);

    scrollTab();

    ajax_calltrack_number();

    myTabs();
    /*para utilizar as tabs é necessário adicionar a class my-tabs nos elementos que serão utilizados como navegação,
     * como li's, por exemplo. O elementos de navegação também deverão ter o atributo data-content(que informará o id do elemento
     * com o conteúdo) e data-group(que gera o vínculo entre os elementos de navegação e os elementos com conteúdo),
     * para que possa ser utilizado mais de um conjunto de tabs por página, cada conjunto deve ter seu próprio data-group.
     *
     * Nos elementos que apresentam o conteúdo, deverá ser adicionada a class .my-tabs-content, e o id para vincular com o elemento de navegação.*/


    //função para loadmore
    $('.load-more').each(function () {
        var inicial = $(this).data("inicial");
        $(' li:lt(' + inicial + ')', this).show();
    });

    $('.btn-load-more').click(function () {
        var $container = $(this).parent("div");
        var $ul = 'ul';
        var $li = 'ul li';

        var size_li = $container.find($li).size();
        var load = $container.find($ul).data("load");
        var inicial = $container.find($ul).data("inicial");
        var menuop = $container.find($ul).data("menu-load");

        if (this['menu_' + menuop] == null) {
            this['menu_' + menuop] = inicial;
        }

        this['menu_' + menuop] = +(this['menu_' + menuop] + load <= size_li) ? this['menu_' + menuop] + load : size_li;
        $($container.find('ul li:lt(' + this['menu_' + menuop] + ')')).slideDown();

        if (size_li == this['menu_' + menuop]) {
            $container.find('.btn-load-more').hide();
        }
    });

});//document.ready end

/* input file name */
$(".custom-file-input").on("change", function(){
    $(this).next('.custom-file-control').addClass("active").html($(this).val());
});

function success(pos) {

    var crd = pos.coords;

    $('input[name=TOKEN]').parents('form').append('<input type="hidden" name="LOCALIZACAO_LAT_LONG" value="' + crd.latitude + ',' + crd.longitude + '" /> ');
    $('input[name=TOKEN]').parents('form').append('<input type="hidden" name="LOZALIZACAO_PRECISAO" value="' + crd.accuracy + '" /> ');

};

function error(err) {
    console.log('ERROR(' + err.code + '): ' + err.message);
    ipInfo();
};

var ipInfoTentativas = 0;

function ipInfo() {

    $.ajax({
            url: '//rel.leadforce.com.br/ws/geoip/' + CLIENT_IP,
            dataType: 'json',
        })
        .done(function (data) {
            $('input[name=TOKEN]').parents('form').append('<input type="hidden" name="LOCALIZACAO_LAT_LONG" value="' + data.latitude + ',' + data.longitude + '" /> ');
        });

}

function ajax_calltrack_number() {
    //Na Ordem de exibição
    var number = "0";
    $("[calltrack]").each(function (index) {
        if ($(this).attr('calltrack') != '') {
            number += "," + $(this).attr('calltrack');
        }
    });
    var posicao_numero = number.split(",");
    $.ajax({
        type: "POST",
        url: '//rel.leadforce.com.br/ws/busca_call_track',
        async: true,
        data: {token: number},
        dataType: 'json',
        success: function (json) {
            $.each(json, function (i, item) {
                var str_number = json[i].TELEFONE_ATIVO;
                var telefone = "";
                telefone += '(' + str_number.substring(0, 3) + ") ";
                telefone += str_number.substring(3, 7);
                telefone += " " + str_number.substring(7);
                $("[calltrack=" + json[i].TOKEN + "]").html(telefone).fadeIn();
                $("[calltrack=" + json[i].TOKEN + "]"). attr('href', 'tel:' + str_number);
            });
        }, complete: function () {
            $('[calltrack]').fadeIn();
        }
    });
}

// my-tabs
// Essa funçao serve para trabalharmos com troca de multiplos conteúdos através de Fade Jquery, podendo trabalhar de forma individual cada um
function myTabs() {
    //initialize
    $(".my-tabs-content").hide();
    $(".my-tabs.active").each(function () {
        var tab_ativa = $(this).attr("data-content");
        $("#" + tab_ativa).show();
    });
    //initialize

    $(".my-tabs").click(function () {
        var tab_group = $(this).data('group');
        if ($(this).siblings().length > 0) {
            $(".my-tabs[data-group='" + tab_group + "']").removeClass("active");
            $(this).addClass("active");
            $(".my-tabs-content[data-group='" + tab_group + "']").hide()
            $("#" + $(this).attr("data-content")).fadeIn();
        }
    });
}
// my-tabs


function scrollTab( calc ) {
    // Funcao utilizada para trabalharmos com ancoragens animadas, essa funcao trabalha individualmente com cada elemento
    // Tendo a possibilidade de través do parametro calc, estabelecer uma altura de desconto para essa animação
    if ( calc != null ) {
        $('[data-scroll]').click(function () {
            var obj = $(this).data('scroll');

            $('html, body').animate({
                scrollTop: ($("[scroll=" + obj + "]").offset().top - calc.outerHeight() )
            });
        });
    }else {
        $('[data-scroll]').click(function () {
            var obj = $(this).data('scroll');

            $('html, body').animate({
                scrollTop: ($("[scroll=" + obj + "]").offset().top)
            });
        });
    }
}

//função para abrir pop-up
function popUp(selector){
    $("body,html").addClass("scroll-lock");
    selector.fadeIn("fast");
}
//fechar pop-up no esc
$(document).keydown(function(e) {
    if (e.keyCode == 27) {
        $(".mask").fadeOut();
        $("body,html").removeClass("scroll-lock");
    }
});
//fechar pop-up
$(".mask, .btn-pop-close").on("click",function(){
    $(".mask").fadeOut();
    $("body,html").removeClass("scroll-lock");
}).on("click", ".pop-up-content", function(event){
    event.stopPropagation();
});

if($(window).width() > 1024){
    /* Página de erro */
    var header_height = jQuery("header").outerHeight();
    var page_height = jQuery(window).outerHeight();
    var sitemap_height = jQuery(".site-map").outerHeight();
    var footer_height = jQuery("footer").outerHeight();

    var content_height = page_height - header_height - sitemap_height - footer_height;
    jQuery("#error_page").height(content_height + 'px');
    /* End Página de erro */
}

//filtros de seminovos

//Referência para rotas: https://github.com/OtavioMoreira/Google_Maps_Autocomplete_Routes


function owl_sync(sync1, sync2, nav_sync1, nav_sync2, dots_sync1, dots_sync2, items_mobile, items_tablet, items_desktop) {
    var syncedSecondary = true;
    var slidesPerPage;
    var window_width = $(window).width();

    if (window_width < 769) {
        slidesPerPage = items_mobile;
    } else if (window_width > 768 && window_width < 1025) {
        slidesPerPage = items_tablet;
    } else {
        slidesPerPage = items_desktop;
    }

    sync1.owlCarousel({
        items: 1,
        slideSpeed: 2000,
        nav: nav_sync1,
        autoplay: false,
        autoHeight: true,
        dots: dots_sync1,
        navText: ['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>'],
        loop: false,
        responsiveRefreshRate: 200,
    }).on('changed.owl.carousel', syncPosition);

    sync2
        .on('initialized.owl.carousel', function() {
            sync2.find(".owl-item").eq(0).addClass("current");
        })
        .owlCarousel({
            items: slidesPerPage,
            dots: dots_sync2,
            nav: nav_sync2,
            smartSpeed: 200,
            slideSpeed: 500,
            slideBy: slidesPerPage, //alternatively you can slide by 1, this way the active slide will stick to the first item in the second carousel
            responsiveRefreshRate: 100
        }).on('changed.owl.carousel', syncPosition2);

    function syncPosition(el) {
        //if you set loop to false, you have to restore this next line
        var current = el.item.index;

        sync2
            .find(".owl-item")
            .removeClass("current")
            .eq(current)
            .addClass("current");
        var onscreen = sync2.find('.owl-item.active').length - 1;
        var start = sync2.find('.owl-item.active').first().index();
        var end = sync2.find('.owl-item.active').last().index();

        if (current > end) {
            sync2.data('owl.carousel').to(current, 100, true);
        }
        if (current < start) {
            sync2.data('owl.carousel').to(current - onscreen, 100, true);
        }
    }

    function syncPosition2(el) {
        if (syncedSecondary) {
            var number = el.item.index;
            sync1.data('owl.carousel').to(number, 100, true);
        }
    }

    sync2.on("click", ".owl-item", function(e) {
        e.preventDefault();
        var number = $(this).index();
        sync1.data('owl.carousel').to(number, 300, true);
    });
}