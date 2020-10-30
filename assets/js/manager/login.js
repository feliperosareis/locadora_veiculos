
function win(enderecoURL){
    
    $.fancybox({
        'padding': 40,
        'autoScale':false,
        'overlayShow': true,
        'overlayColor': '#000',
        'overlayOpacity': 1,
        'width': 400,
        'height': 200,
        'href' : enderecoURL,
        'scrolling'   : 'auto',
        'type': 'iframe'

    }); 
}    