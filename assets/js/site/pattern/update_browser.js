jQuery(document).ready(function() {
    
    if(jQuery(window).width() > 1024) {

        $( "#btnCloseUpdateBrowser" ).click(function() {
            $( "#alert_update" ).fadeOut( "slow");
        
            setTimeout(function(){
                $( "#alert_update" ).fadeIn( "slow");
            }, 15000);

        });

        var height_window = jQuery( window ).height();
        jQuery('.browser').css('height', height_window + 'px');
    }
});