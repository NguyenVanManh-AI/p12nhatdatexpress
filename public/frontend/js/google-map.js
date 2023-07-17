var bando;
var infowindow;

function initmap() {
    var pyrmont = new google.maps.LatLng(11.2333, 78.1667);
    bando = new google.maps.Map(document.getElementById('google-map'), {
        center: pyrmont,
        zoom: 15
    });

    var request = {
        location: pyrmont,
        radius: 5500,
        types: ['atm']
    };
    infowindow = new google.maps.InfoWindow();
    var service = new google.maps.places.PlacesService(bando);
    // service.nearbySearch(request, callback);
}

function callback(results, status) {
    if (status == google.maps.places.PlacesServiceStatus.OK) {
        for (var i = 0; i < results.length; i++) {
            createMarker(results[i]);
        }
    }
}

function createMarker(place) {
    var placeLoc = place.geometry.location;
    var marker = new google.maps.Marker({
        map: map,
        position: place.geometry.location
    });

    google.maps.event.addListener(marker, 'click', function() {
        infowindow.setContent(place.name);
        infowindow.open(bando, this);
    });
}

google.maps.event.addDomListener(window, 'load', initialize);
