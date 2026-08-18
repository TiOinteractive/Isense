(function ($) {
    $.fn.OpenStreetMap = function (container, options) {
        $this = this;
        this.map = function () {
            // Token dostarcza backend (ustawienia mapy lub mapbox.accessToken w .env) — nie trzymamy go w repo.
            let mapbox_url = 'https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=' + (this.options.mapbox_token || '');
            let mapbox_attribution = '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors';
            let grayscale = L.tileLayer(mapbox_url, {id: 'mapbox/light-v9', tileSize: 512, zoomOffset: -1, attribution: mapbox_attribution}),
                streets   = L.tileLayer(mapbox_url, {id: 'mapbox/streets-v11', tileSize: 512, zoomOffset: -1, attribution: mapbox_attribution}),
                satellite = L.tileLayer(mapbox_url, {id: 'mapbox/satellite-v9', tileSize: 512, zoomOffset: -1, attribution: mapbox_attribution});
            let base_maps = {
                "Grayscale": grayscale,
                "Streets": streets,
                "Satellite": satellite
            };
            
            let osm_map = L.map(this.container, {
                center: [this.options.lat, this.options.lng],
                zoom: this.options.zoom,
                layers: [grayscale]
            });
            
            let layerControl = L.control.layers(base_maps).addTo(osm_map);
                    
            let markers = JSON.parse(this.options.markers);
            for (var i = 0; i < markers.length; i++) {
                let draftMarker = markers[i];
                let icon = '';
                if(draftMarker.icon) {
                    icon = {
                        url: draftMarker.icon,
                        anchor: new google.maps.Point(draftMarker.icon_width / 2, draftMarker.icon_height)
                    };
                    icon = L.icon({
                        iconUrl: draftMarker.icon,
                        shadowUrl: '',
                        iconSize:     [draftMarker.icon_width, draftMarker.icon_height], // size of the icon
                        shadowSize:   [0, 0], // size of the shadow
                        iconAnchor:   [draftMarker.icon_width/2, draftMarker.icon_height], // point of the icon which will correspond to marker's location
                        shadowAnchor: [0, 0],  // the same for the shadow
                        popupAnchor:  [0, -draftMarker.icon_height] // point from which the popup should open relative to the iconAnchor
                    });
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
                
                L.marker([draftMarker.lat, draftMarker.lng], {
                    icon: icon
                }).addTo(osm_map)
                    .bindPopup(draftMarker.text);
            }
            
        }

        this.options = options;
        this.container = container;
        this.map();

    };

})(jQuery);