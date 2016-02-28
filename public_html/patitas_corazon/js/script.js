(function (window, $) {
    function scrollTo(target) {
        var wheight = $(window).height() / 2;
        var alto = $("#header-top").height() + $("#header").height();

        var ooo = $(target).offset().top - alto;
        $('html, body').animate({scrollTop: ooo}, 600);
    }

    function adicionarMarcador(mapa, lat, lng, info, img) {
        var latlng = new google.maps.LatLng(lat, lng);
        var marker = new google.maps.Marker({
            position: latlng,
            map: mapa,
            html: info,
            icon: img,
            animation: google.maps.Animation.DROP,
            draggable: true
        });

        var infoWindow = new google.maps.InfoWindow({
            content: marker.html
        });
        infoWindow.open(mapa, marker);

        google.maps.event.addListener(marker, "click", function () {
            infoWindow.setContent(this.html);
            infoWindow.open(mapa, this);
        });

        return marker;
    }

    $(document).ready(function () {
        $(window).resize(function () {
            if ($("#menu-resp").is(":visible")) {
                $("#nav_menu").hide();
            } else {
                $("#nav_menu").show();
            }
        });

        $("#menu_resp > a").click(function (e) {
            e.preventDefault();
            $("#nav_menu").toggle("slow");
        });

        $("section#banner ul").lightSlider({
            item: 1,
            auto: true
        });

        $('.galeria a').featherlightGallery();

        if (document.getElementById("mapa")) {
            var options = {
                zoom: 17,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };
            var map = new google.maps.Map(document.getElementById("mapa"), options);
            var marker = adicionarMarcador(map, 3.3841693, -76.5273077, 'Patitas de Corazón', undefined);
            map.setCenter(marker.getPosition());
        }
    });
})(window, jQuery);