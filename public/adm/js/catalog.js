$(function(){
    $('.catalog-form select[name="type"]').on('change', function(){
        let type = $(this).val();
        $('.catalog-form .type-option').addClass('hidden');
        $('.catalog-form .type-option.' + type).removeClass('hidden');
        $('.fileupload[data-field="photos"]').data('option', type);
    });
    
    let cords = $('.cords-field').val().split(',');
    
    let myLatlng = new google.maps.LatLng(cords[0], cords[1]);
    let center = this.myLatlng;
    let zoom = 14;
    let styles = '[]';
    let myMapTypeControlOptions = {
        mapTypeIds: [google.maps.MapTypeId.ROADMAP, google.maps.MapTypeId.SATELLITE, google.maps.MapTypeId.HYBRID],
        style: google.maps.MapTypeControlStyle.DROPDOWN_MENU
    };

    let options = {
        zoom: zoom,
        styles: styles,
        center: myLatlng,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        mapTypeControlOptions: myMapTypeControlOptions
    };

    let map = new google.maps.Map(document.getElementById('map'), options);
    
    let marker = new google.maps.Marker(
    {
        map:map,
        draggable:true,
        animation: google.maps.Animation.DROP,
        position: myLatlng
    });
    google.maps.event.addListener(marker, 'dragend', function() 
    {
        let pos = marker.getPosition();
        $('.cords-field').val(pos.lat() + ',' + pos.lng());
    });
    
    
    $('.link-box.active .address-field').on('change', function(){
        var geocoder = new google.maps.Geocoder();
        geocoder.geocode({
            "address": $(this).val(),
        }, function(results) {
            let pos = results[0].geometry.location;
            $('.cords-field').val(pos.lat() + ',' + pos.lng());
            marker.setPosition(pos);
            map.setCenter(pos);
        });
    });
    
    $('.cords-field').on('change', function(){
        let cords = $(this).val().split(',');
        var pos = new google.maps.LatLng(cords[0], cords[1]);
        marker.setPosition(pos);
        map.setCenter(pos);
    });
});