let map;
let marker;
let geocoder;
let address;
var infoWindow;

let mangKhoaReplaceToEmpty = ['Thành phố', 'Quận', 'City', 'District', 'Province', 'TP. ']

const initLocation = {lat: 10.8451316, lng: 106.6927875};

const mapOptions = {
    zoom: 16,
    center: initLocation,
}

/**
 * Initial Map
 * @param mapId
 * @param elementLat
 * @param elementLng
 * @returns {{}}
 */
async function initMap(mapId, elementLat, elementLng, initLocation = null) {
    if (initLocation && initLocation.lat && initLocation.lng) {
        mapOptions.lat = initLocation.lat
        mapOptions.lng = initLocation.lng
    } else {
        getLocation()
            .then((data) => {
                defaultCoor = data
            })
            .catch(()=>{})
            .finally(() => {
                mapOptions.lat = defaultCoor.lat,
                mapOptions.lng = defaultCoor.long
            })
    }

    map = new google.maps.Map(document.getElementById(mapId), mapOptions);
    geocoder = new google.maps.Geocoder();
    infoWindow = new google.maps.InfoWindow();

    if (mapOptions.lat && mapOptions.lng) placeMarker({lat: mapOptions.lat, lng: mapOptions.lng}, `${mapOptions.lat},${mapOptions.lng}`, elementLat, elementLng)
    addFindMyLocation(elementLat, elementLng);
    google.maps.event.addListener(map, "click", (event) => {
        markerToAddress({location: event.latLng}, elementLat, elementLng).then((r) => callbackMarkerToAddress(r));
    });
}

function getLocation () {
    return new Promise(function (resolve, reject) {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function (location) {
                const coordinates = {lat: location.coords.latitude, long: location.coords.longitude};
                resolve(coordinates);
            });
        }
        else {
            reject('Location not found!');
        }
    })
}

/**
 * Place Marker
 * @param location
 * @param label
 * @param elementLat
 * @param elementLng
 * @returns {{}}
 */
function placeMarker(location, label, elementLat, elementLng) {
    if (marker) {
        marker.setPosition(location);
    } else {
        addMarker(location)
    }
    updateLatLng(location, elementLat, elementLng)
    map.setCenter(location);
    makeInfoWindowEvent(marker, label)
    marker.addListener('dragend', (event) => {
        markerToAddress({location: event.latLng}, elementLat, elementLng)
    });
}

/**
 * Add Marker to map
 * @param location
 * @returns {{}}
 */
function addMarker(location) {
    marker = new google.maps.Marker({
        position: location,
        draggable: false, // Chinh disable drag
        map: map,
    });
    return marker;
}

/**
 * Geo code by request
 * @param request
 * @param elementLat
 * @param elementLng
 * @returns {{}}
 */
function geocode(request, elementLat, elementLng) {
    geocoder
        .geocode(request)
        .then((result) => {
            const {results} = result;
            placeMarker(results[0].geometry.location, results[0].formatted_address, elementLat, elementLng)
            return results[0];
        })
        .catch((e) => {
            console.log("Geocode was not successful for the following reason: " + e);
            // alert("Geocode was not successful for the following reason: " + e);
        });
}

/**
 * Counter
 * @param array
 * @returns {{}}
 */
function Counter(array) {
    var count = {};
    array.forEach(val => count[val] = (count[val] || 0) + 1);
    return count;
}

/**
 * Get Value of Max
 * @param obj
 * @returns value
 */
function getMaxFromObject(obj) {
    let highestVal = Math.max.apply(null, Object.values(obj));
    val = Object.keys(obj).find(function (a) {
        return obj[a] === highestVal;
    });
    return val;
}

/**
 * Check once Object
 * @param component
 * @returns {*[]|*}
 */
function checkOnceObject(component) {
    let address_components = component.address_components;
    let formatted_address = component.formatted_address;
    let khaNghis = [];
    // note
    for (let component of address_components) {
        if (component.types.some(i => i.trim().toLowerCase().includes("administrative_area_level_3"))) {
            return component.long_name;
        }
    }
    let textList = formatted_address.split(", ");
    let boxList = address_components.map(item => [item.long_name, item.short_name]);

    for (let text of textList) {
        let isExist = false;
        for (let box of boxList) {
            isExist = box.map(item => item.toLowerCase().trim()).some(item => {
                return text.toLowerCase().trim().includes(item);
            });

            if (isExist) break;
        }
        if (isExist == false) khaNghis.push(text);
    }
    return khaNghis;
}

function khaNghisCombine(results) {
    let componentNames = results.map(result => {
        return checkOnceObject(result);
    });
    let data = {};
    for (let item of componentNames) {
        if (typeof (item) === "string") {
            data[item] = 1;
            return getMaxFromObject(data);
        }
    }
    let to1DimentionArrray = [].concat(...componentNames);
    return getMaxFromObject(Counter(to1DimentionArrray));
}

/**
 * Geocode from lat & long to address
 * @param request
 * @param elementLat
 * @param elementLng
 * return {address, {lat,lng}}
 */
function markerToAddress(request, elementLat = null, elementLng = null) {
    let country_name = null;
    let province_name = null;
    let district_name = null;
    let road_name = null;
    let street_number = null;
    let ward_name = null;
    let formatted_address = null;
    let latLng = request.location.toJSON();

    return geocoder.geocode(request)
        .then((result) => {
            const {results} = result;
            formatted_address = results[0].formatted_address
            if (elementLat && elementLng)
                placeMarker(results[0].geometry.location, results[0].formatted_address, elementLat, elementLng)
            address = results[0]
            ward_name = khaNghisCombine(results);
            // Nếu tên phường có số
            let num;
            num = ward_name.search(/\p{L}\s\d+/gu) > 0 ? num = ward_name.indexOf(ward_name.match(/\d+/)[0]) : -1;
            ward_name = ward_name.toLowerCase().indexOf('phường') === 0 && num === 7 ? ward_name.match(/\d+/)[0] : ward_name;
            if (parseInt(ward_name) < 10) ward_name = '0' + ward_name;
            address.address_components.forEach((item) => {
                item.types.forEach((type) => {
                    switch (type) {
                        case "country":
                            country_name = item.long_name;
                            break
                        case "administrative_area_level_1":
                            province_name = item.long_name;
                            for (let key of mangKhoaReplaceToEmpty) {
                                province_name = province_name.replace(key, "").trim()
                            }
                            break;
                        case "administrative_area_level_2":
                            district_name = item.long_name;
                            for (let key of mangKhoaReplaceToEmpty) {
                                district_name = district_name.replace(key, "").trim()
                            }
                            break;
                        // Huyện cấp địa phương
                        case "locality":
                            district_name = item.long_name;
                            for (let key of mangKhoaReplaceToEmpty) {
                                district_name = district_name.replace(key, "").trim()
                            }
                            break;
                        case "route":
                            road_name = item.long_name;
                            break;
                        case "street_number":
                            street_number = item.long_name;
                            break;
                    }
                })
            })

            return {
                country_name,
                province_name,
                district_name,
                ward_name,
                road_name,
                street_number,
                formatted_address,
                latLng
            };
        })
        .catch((e) => {
            console.log("Định vị không thành công vì lý do: " + e);
        });
}

/**
 * Make popup detail of marker
 * @param marker
 * @param contentString
 */
function makeInfoWindowEvent(marker, contentString) {
    if (infoWindow) infoWindow.close(map, marker);
    google.maps.event.addListener(marker, 'click', function () {
        infoWindow.setContent(contentString);
        infoWindow.open(map, marker);
    });
}

/**
 * Update lat lng into input
 * @param location
 * @param elementLat
 * @param elementLng
 */
function updateLatLng(location, elementLat, elementLng) {
    let latLng;
    try {
        latLng = location.toJSON();
    } catch (e) {
        latLng = location;
    }
    $(elementLat).val(latLng.lat)
    $(elementLng).val(latLng.lng)
}

/**
 * Find my location
 */
function addFindMyLocation(elementLat, elementLng) {
    var controlDiv = document.createElement('div');

    $(controlDiv).addClass('test')

    var firstChild = document.createElement('div');
    firstChild.style.backgroundColor = '#fff';
    firstChild.style.border = 'none';
    firstChild.style.outline = 'none';
    firstChild.style.width = '40px';
    firstChild.style.height = '40px';
    firstChild.style.borderRadius = '2px';
    firstChild.style.boxShadow = '0 1px 4px rgba(0,0,0,0.3)';
    firstChild.style.cursor = 'pointer';
    firstChild.style.marginRight = '10px';
    firstChild.style.padding = '0';
    firstChild.style.display = 'flex';
    firstChild.style.alignItems = 'center';
    firstChild.title = 'Vị trí của bạn';
    controlDiv.appendChild(firstChild);

    var secondChild = document.createElement('div');
    secondChild.style.margin = '10px';
    secondChild.style.width = '20px';
    secondChild.style.height = '20px';
    secondChild.style.backgroundImage = 'url(https://maps.gstatic.com/tactile/mylocation/mylocation-sprite-2x.png)';
    secondChild.style.backgroundSize = '187px 18px';
    secondChild.style.backgroundPosition = '1 0';
    secondChild.style.backgroundRepeat = 'no-repeat';
    firstChild.appendChild(secondChild);

    google.maps.event.addListener(map, 'center_changed', function () {
        secondChild.style['background-position'] = '0 0';
    });

    firstChild.addEventListener('click', function () {
        // Try HTML5 geolocation.
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                (position) => {
                    const pos = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude,
                    };
                    let loc = new google.maps.LatLng(pos.lat, pos.lng)
                    infoWindow.setPosition(pos);
                    infoWindow.open(map);
                    map.setCenter(pos);
                    markerToAddress({location: loc}, elementLat, elementLng).then((r) => callbackMarkerToAddress(r));
                },
                () => {
                    handleLocationError(true, infoWindow, map.getCenter());
                }
            );
        } else {
            handleLocationError(false, infoWindow, map.getCenter());
        }
    });
    controlDiv.index = 1;
    map.controls[google.maps.ControlPosition.RIGHT_BOTTOM].push(controlDiv);
}

/**
 * Handle Error Find Location
 * @param browserHasGeolocation
 * @param infoWindow
 * @param pos
 */
function handleLocationError(browserHasGeolocation, infoWindow, pos) {
    infoWindow.setPosition(pos);
    infoWindow.setContent(
        browserHasGeolocation
            ? "Lỗi: Định vị vị trí thất bại."
            : "Lỗi: Trình duyệt của bạn không hỗ trợ định vị vị trí."
    );
    infoWindow.open(map);
}

/**
 * Get My Location To Address
 */
function getMyLocationToAddress() {
    // Try HTML5 geolocation.
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            function (position) {
                const pos = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude,
                };
                let loc = new google.maps.LatLng(pos.lat, pos.lng)
                markerToAddress({location: loc}).then((r) => callbackLocationToAddress(r));
            },
            function () {
                $('.accept-location-instruct').addClass('in')
                // alert('Không hỗ trợ định vị');
            }
        );
    } else {
        $('.accept-location-instruct').addClass('in')
        // alert('Không hỗ trợ định vị');
    }
}

// should check and change or remove it
function setLocationToAddress() {
    // Try HTML5 geolocation.
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            function (position) {
                const pos = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude,
                };
                let loc = new google.maps.LatLng(pos.lat, pos.lng)
                markerToAddress({location: loc}).then((r) => callBackSetLocationAddress(r));
            },
            function () {
                // alert('Không hỗ trợ định vị');
            }
        );
    } else {
        // alert('Không hỗ trợ định vị');
    }
}
// end should check and change or remove it
