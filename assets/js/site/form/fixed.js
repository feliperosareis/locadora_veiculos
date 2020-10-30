$("#form .options select").on('change', function(){
    var form_selected = $(this).val();
    var obj = $( '#' + form_selected);

    if ( form_selected != 0 && $( obj ).is(':visible') == false ) {
        $("#form .box").slideUp();
        $( obj ).slideDown();
    }
});