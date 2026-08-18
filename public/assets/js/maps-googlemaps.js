var map, center, zoom;

(function ($) {
    $.fn.GoogleMap = function (container, options) {
        $this = this;
        this.map = function () {
            this.myLatlng = new google.maps.LatLng(this.options.lat, this.options.lng);
            let center = this.myLatlng;
            let zoom = this.options.zoom;
            let styles = this.options.styles;
            let myMapTypeControlOptions = {
                mapTypeIds: [google.maps.MapTypeId.ROADMAP, google.maps.MapTypeId.SATELLITE, google.maps.MapTypeId.HYBRID],
                style: google.maps.MapTypeControlStyle.DROPDOWN_MENU
            };
            
            let options = {
                zoom: this.options.zoom,
                styles: this.options.styles,
                center: this.myLatlng,
                mapTypeId: google.maps.MapTypeId.ROADMAP,
                mapTypeControlOptions: myMapTypeControlOptions
            };
            
            this.map = new google.maps.Map(document.getElementById(this.container), options);
            map = this.map;

            let markers = JSON.parse(this.options.markers);
            console.log(markers);

            for (var i = 0; i < markers.length; i++) {
                let draftMarker = markers[i];
                let myLatLng = new google.maps.LatLng(draftMarker.lat, draftMarker.lng);
                let icon = '';
                if(draftMarker.icon) {
                    icon = {
                        url: draftMarker.icon,
                        anchor: new google.maps.Point(draftMarker.icon_width / 2, draftMarker.icon_height)
                    };
                } else {
                    icon = {
                        path: draftMarker.svg,
                        fillColor: "blue",
                        fillOpacity: 0.6,
                        strokeWeight: 0,
                        rotation: 0,
                        scale: 0.6,
                        anchor: new google.maps.Point(15, 35),
                    };
                }
                let marker = new google.maps.Marker({
                    position: myLatLng,
                    map: this.map,
                    title: draftMarker.title,
                    icon: icon,
                    text: draftMarker.text,
                    lat: draftMarker.lat,
                    lng: draftMarker.lng
                });


                if (draftMarker.text) {
                    let findmarker = this.options.findmarker;
                    google.maps.event.addListener(marker, 'click', function () {
                        var info_text = '<div class="googlemap_window">' + this.text + '</div>';
                        infowindow = new google.maps.InfoWindow({
                            content: info_text
                        });
                        infowindow.open($this.map, this);
                    });
                }
            }
        }

        this.options = options;
        this.container = container;
        this.map();

    };

})(jQuery);