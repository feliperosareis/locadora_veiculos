
function win(enderecoURL, w, h, strTitulo) {

    $.fancybox({
        'padding': 20,
        'autoScale': true,
        'overlayShow': true,
        'transitionIn': 'elastic',
        'transitionOut': 'elastic',
        'title': strTitulo,
        'titleShow': true,
        'titlePosition': 'inside',
        'width': w,
        'height': h,
        'href': enderecoURL,
        'scrolling': 'auto',
        'type': 'iframe'
    });
}

/* Essa funcao não esta concluida, possivelmente essa abordagem para upload 
 * vai ser descontinuada em prol do ImageCRUD
 */
function upload(url, strTitulo) {
    win(url, 430, 500, strTitulo)
}



function scrollbarMenuManager(){
    var oScrollbar = $('#scrollMenuManager');
   oScrollbar.tinyscrollbar({ axis: 'x'});
}



function hideScrollbar(){
    var oHideScrollbar = $('#scrollMenuManager');
    
    oHideScrollbar.bind({
      mouseenter: function() {
        $(this).find('.track').animate({'height':'6px', 'top':'-3px'}, 100);
        $(this).find('.thumb').animate({'height':'6px'}, 100);
      },
      mouseleave: function() {
        $(this).find('.track').animate({'height':'0px', 'top':'0'}, 100);
        $(this).find('.thumb').animate({'height':'0px'}, 100);
      }
    });
   
}

function setIframeHeight(id) {
    var tamanho = $('#' + id).contents().find('.flexigrid').height();
    var tamanho_report = $('#' + id).contents().find('#report-success').height();
        tamanho_report += tamanho_report > 0 ? 45 : 0;
//    alert(tamanho_report);
    $("#" + id).animate({height: (tamanho + tamanho_report) + "px"});
}