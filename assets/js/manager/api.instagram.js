// By Nissius //
$(document).ready(function() {
    $("#main-table-box").before("<div class='tDiv'>\n\
                                    <div class='tDiv2'>\n\
                                        <a class='jquery' href='../assets/img/ajaxloadergreen.gif' onclick='javascript:buscar_novos_feeds()' title='Buscando Feeds Novos' class='add-anchor'>\n\
                                            <div class='fbutton'>\n\
                                                <div>\n\
                                                    <span class='instagram' title='Buscar Novos Feeds'>Buscar Novos Feeds</span>\n\
                                                </div>\n\
                                    </div></a>\n\
                                    <div class='btnseparator'></div>\n\
                                </div>"); 
     
});

function buscar_novos_feeds(){
     //alert('Estamos fazendo a busca por novos feeds.\nClique em OK e AGUARDE a mensagem de confirmação');
     $.post("instagram_feeds/novos_feeds").always(function() {
            alert('Novos Feeds adicionados com sucesso!');
            $.fancybox.close();
            location.reload();
     });
}

function atualizar_curtidas_feeds(){
     //alert('Estamos atualizando o número de curtição das imagens!Clique em OK e AGUARDE a mensagem de confirmação!')
     $.post("instagram_feeds/atualiza_curtidas").always(function() {
            alert('Atualizado com sucesso!');
            $.fancybox.close();
            location.reload();
     });
}
$(document).ready(function(){
    $(".jquery").fancybox({       
        autoDimensions: true,
        centerOnScroll: true,
        modal: true,
        overlayColor: '#fff',
        opacity: true,
        overlayShow: false,
        padding: 0,
        overlayOpacity: 0.85,
        onStart: function(){
                $(".fancybox-bg").removeAttr('id');
                $("#fancybox-content").css('borderWidth','0px');
                $("#fancybox-content").css('background','transparent');
                $("#fancybox-outer").css('background','transparent');
        }
        
    }); 
});
   