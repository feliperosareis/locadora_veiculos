if ( $('.map-value').length > 0 ) {
    $.getScript("//maps.googleapis.com/maps/api/js?key=AIzaSyCSt8SF5CV4BAyHIuZcwWUiig79ltF2XzM", function () {
        $('.map-value').each(function () {
            var obj = $(this).attr('id');
            var map;
            var newmarker;

            function initialize() {
                var latlng = $('#' + obj).data("coordinates").split('|');

                map = new google.maps.Map(document.getElementById(obj), {
                    zoom: 15,
                    scrollwheel: false,
                    center: {lat: 1 * latlng[0], lng: 1 * latlng[1]}
                });

                newmarker = new google.maps.Marker({
                    map: map,
                    position: {lat: 1 * latlng[0], lng: 1 * latlng[1]}
                });
            }

            if (isIE() <= 9 && isIE() != false) {
                initialize();
            } else {
                google.maps.event.addDomListener(window, 'load', initialize);
            }
        });
    });

    function isIE() {
        var myNav = navigator.userAgent.toLowerCase();
        return (myNav.indexOf('msie') != -1) ? parseInt(myNav.split('msie')[1]) : false;
    }
}

$(".btn-close").click(function() {
    $(".comparador-wrapper").slideUp();
});