var map;
var marker;
async function initMap() {
    //VietNam coordinates
    var defaultCoor = {lat: 21.030653,long: 105.847130 };
    //classified coordinate
    let latitude = $('input[name=latitude]').val();
    let longtitude = $('input[name=longtitude]').val();
    if (latitude && longtitude) {
        defaultCoor.lat = latitude;
        defaultCoor.long = longtitude;
    }

    getLocation()
        .then((data) => {defaultCoor = data; })
        .catch(()=>{})
        .finally(() => {
            var defaultLocation = new google.maps.LatLng(defaultCoor.lat, defaultCoor.long);
            let infowindow = new google.maps.InfoWindow();
            map = new google.maps.Map(document.getElementById('google-map'), {center: defaultLocation, zoom: 15});
        })

}

function getLocation () {
    let userLocation =  new Promise(function (resolve, reject) {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function (location) {
                coordinates =  {lat: location.coords.latitude, long: location.coords.longitude};
                resolve(coordinates);

            });
        }
        else {
            reject('Location not found!');
        }
    })
    return userLocation;
}


function createMarker(place) {
    marker = new google.maps.Marker({position: place.geometry.location, map: map});
}

$(document).ready(function () {
    // $('input[name=address],.province,.district,.ward').on('change', function () {
    $('input[name=address], [name="province"], [name="district"]').on('change', function () {
        google.maps.event.trigger(map, 'resize');
        let province = $('[name="province"] option:selected').text();
        let district = $('[name="district"] option:selected').text();
        // let ward = $('.ward option:selected').text();
        let address = $('input[name=address]').val();
        var request = {
            // query: `${address}, ${ward}, ${district}, ${province}`,
            query: `${address}, ${district}, ${province}`,
            fields: ['name', 'geometry'],
        };

        var service = new google.maps.places.PlacesService(map);
        service.findPlaceFromQuery(request, function (results, status) {
            if (status === google.maps.places.PlacesServiceStatus.OK) {
                marker ? marker.setMap(null) : null;
                createMarker(results[0]);
                $('input[name=latitude]').val(results[0].geometry.location.lat());
                $('input[name=longtitude]').val(results[0].geometry.location.lng());
                map.setCenter(results[0].geometry.location);
            }
        });
    });
});


