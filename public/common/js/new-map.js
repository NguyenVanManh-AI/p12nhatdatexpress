const __replaceToEmptyMaps = ['Thành phố', 'Quận', 'City', 'District', 'Province', 'TP. '],
      __defaultLocation = {
            lat: 10.8451316, // tphcm
            lng: 106.6927875
            // 21.030653 // ha noi
            // 105.847130
        },
        __mapZoom = 16,
        __utilitiesZoom = 15;

let map,
    marker,
    geocoder,
    address,
    infoWindow,
    __$mapParents,
    __isClickMarker,
    __markerIcon,
    __utilitiesMarkerIcon,
    __utilitiesMarkers = [],
    __loadingUtilities = false,
    __latEle,
    __longEle,
    mapOptions = {
        zoom: __mapZoom,
        center: __defaultLocation,
    },
    __viewMaps = {
        map: null,
        marker: null,
        utilitiesMarkers: [],
        utilitiesMarkerIcon: null,
        loadingUtilities: false,
        markerIcon: null,
        mapOptions: {
            zoom: __mapZoom,
            center: __defaultLocation
        }
    };

/**
 * Initial Map
 * @param mapId
 * @param latElement
 * @param longElement
 * @param initLocation = null
 * @returns {{}}
 */
async function initMap(mapId, latElement, longElement, initLocation = null) {
    if (!$(`#${mapId}`) || !$(`#${mapId}`).length || !latElement || !longElement) return

    __$mapParents = $(`#${mapId}`).parents('.js-has-map-address')
    __latEle = latElement
    __longEle = longElement

    if (initLocation && initLocation.lat && initLocation.lng) {
        mapOptions.lat = initLocation.lat
        mapOptions.lng = initLocation.lng
        mapOptions.center = initLocation
    } else {
        let currentCoordinates;

        await getLocation()
            .then(coordinates => {
                currentCoordinates = coordinates
            })
            .catch(() => {})
            .finally(() => {
                if (!currentCoordinates) return
                mapOptions.lat = currentCoordinates.lat,
                mapOptions.lng = currentCoordinates.long
                mapOptions.center = {
                    lat: currentCoordinates.lat,
                    lng: currentCoordinates.long,
                }
            })
    }

    map = new google.maps.Map(document.getElementById(mapId), mapOptions);
    geocoder = new google.maps.Geocoder();
    infoWindow = new google.maps.InfoWindow({
        disableAutoPan: true
    });

    // marker icon
    if ($(`#${mapId}`).data('marker-icon'))
        __markerIcon = {
            url: $(`#${mapId}`).data('marker-icon'),
            scaledSize: { width: 32, height: 51, f: 'px', b: 'px' },
        };

    // if accept location has lat lng set marker
    if (mapOptions.lat && mapOptions.lng)
        geocoder.geocode({
                location: {
                    lat: mapOptions.lat,
                    lng: mapOptions.lng
                }
            })
            .then(result => {
                const {results} = result;
                placeMarker(
                    results[0].geometry.location,
                    results[0].formatted_address,
                    true
                )
            })
        // placeMarker(
        //     {
        //         lat: mapOptions.lat,
        //         lng: mapOptions.lng
        //     },
        //     `${mapOptions.lat},${mapOptions.lng}`
        // )

    addFindMyLocation();

    google.maps.event.addListener(map, "click", (event) => {
        markerToAddress({
                location: event.latLng
            })
            .then(r => callbackMarkerToAddress(r));
    });
}

/**
 * init simple map with marker & search
 * @param mapId
 * @param initLocation = null
 * @returns {{}}
 */
async function initSimpleMap(mapId, initLocation = null) {
    if (!$(`#${mapId}`) || !$(`#${mapId}`).length) return

    if (initLocation && initLocation.lat && initLocation.lng) {
        mapOptions.lat = initLocation.lat
        mapOptions.lng = initLocation.lng
        mapOptions.center = initLocation
    }

    map = new google.maps.Map(document.getElementById(mapId), mapOptions);
    geocoder = new google.maps.Geocoder();
    infoWindow = new google.maps.InfoWindow({
        disableAutoPan: true
    });

    // marker icon
    if ($(`#${mapId}`).data('marker-icon'))
        __markerIcon = {
            url: $(`#${mapId}`).data('marker-icon'),
            scaledSize: { width: 32, height: 51, f: 'px', b: 'px' },
        };

    if (mapOptions.lat && mapOptions.lng)
        geocoder.geocode({
                location: {
                    lat: mapOptions.lat,
                    lng: mapOptions.lng
                }
            })
            .then(result => {
                const {results} = result;
                placeMarker(
                    results[0].geometry.location,
                    results[0].formatted_address,
                    false
                )
            })
}

/**
 * init simple map with marker & search
 * @param mapId
 * @param initLocation = null
 * @returns {{}}
 */
async function initViewMapSimpleMap(mapId, initLocation = null) {
    if (!$(`#${mapId}`) || !$(`#${mapId}`).length) return

    __viewMaps = {
        map: null,
        marker: null,
        utilitiesMarkers: [],
        utilitiesMarkerIcon: null,
        loadingUtilities: false,
        markerIcon: null,
        mapOptions: {
            zoom: __mapZoom,
            center: __defaultLocation
        }
    };

    if (initLocation && initLocation.lat && initLocation.lng) {
        __viewMaps.mapOptions.lat = initLocation.lat
        __viewMaps.mapOptions.lng = initLocation.lng
        __viewMaps.mapOptions.center = initLocation
    }

    __viewMaps.map = new google.maps.Map(document.getElementById(mapId), mapOptions);

    if (!geocoder)
        geocoder = new google.maps.Geocoder();
    if (!infoWindow)
        infoWindow = new google.maps.InfoWindow({
            disableAutoPan: true
        });

    // marker icon
    if ($(`#${mapId}`).data('marker-icon'))
        __viewMaps.markerIcon = {
            url: $(`#${mapId}`).data('marker-icon'),
            scaledSize: { width: 32, height: 51, f: 'px', b: 'px' },
        };

    if (__viewMaps.mapOptions.lat && __viewMaps.mapOptions.lng)
        geocoder.geocode({
                location: {
                    lat: __viewMaps.mapOptions.lat,
                    lng: __viewMaps.mapOptions.lng
                }
            })
            .then(result => {
                const {results} = result;
                placeViewMapMarker(
                    results[0].geometry.location,
                    results[0].formatted_address,
                    false
                )
            })
}

async function callbackSearchNear(results, status) {
    if (status == google.maps.places.PlacesServiceStatus.OK) {
        map.setZoom(__utilitiesZoom)

        for (var i = 0; i < results.length; i++) {
            marker = createMarker(results[i], false, __utilitiesMarkerIcon);
            __utilitiesMarkers.push(marker)

            let address = null;
            await geocoder.geocode({
                    location: results[i].geometry.location
                })
                .then(result => {
                    const {results} = result;
                    address = results[0].formatted_address
                })

            let label = `<strong>${results[i].name}</strong><br><span>${address}</span>`

            makeInfoWindowEvent(marker, label)
        }
    }

    __viewMaps.loadingUtilities = false
    $('.js-map-load-utilities .map__load-utilities').removeClass('loading')
}

// should combine to 1 function
async function callbackViewMapSearchNear(results, status) {
    if (status == google.maps.places.PlacesServiceStatus.OK) {
        __viewMaps.map.setZoom(__utilitiesZoom)

        for (var i = 0; i < results.length; i++) {
            __viewMaps.marker = createViewMapMarker(results[i], false, __viewMaps.utilitiesMarkerIcon);
            __viewMaps.utilitiesMarkers.push(__viewMaps.marker)

            let address = null;
            await geocoder.geocode({
                    location: results[i].geometry.location
                })
                .then(result => {
                    const {results} = result;
                    address = results[0].formatted_address
                })

            let label = `<strong>${results[i].name}</strong><br><span>${address}</span>`

            makeViewMapInfoWindowEvent(__viewMaps.marker, label)
        }
    }

    __viewMaps.loadingUtilities = false
    $('.popup-view-map__load-utilities').removeClass('loading')
}

const setMapOnAll = (map, markers) => {
    markers.forEach(marker => {
        marker.setMap(map);
    })
}

const hideMarkers = markers => {
    setMapOnAll(null, markers)
}

$('body').on('click', '.js-map-load-utilities .map-utilities__list .map-utilities__item', function () {
    if (!map || __loadingUtilities) return

    __viewMaps.loadingUtilities = true
    $('.js-map-load-utilities .map__load-utilities').addClass('loading')

    $(this).toggleClass('active');
    $(this).siblings().removeClass('active')

    let types = $(this).data('types') ? $(this).data('types').split(',') : null;

    hideMarkers(__utilitiesMarkers)
    map.setCenter(mapOptions.center)

    if (!types || !$(this).hasClass('active')) {
        map.setZoom(__mapZoom)
        __viewMaps.loadingUtilities = false
        $('.js-map-load-utilities .map__load-utilities').removeClass('loading')
        return
    }

    let service = new google.maps.places.PlacesService(map),
        request = {
            location: {
                lat: mapOptions.lat,
                lng: mapOptions.lng
            },
            radius: 5000,
            types: types
        };

    __utilitiesMarkerIcon = $(this).data('icon')
        ?   {
                url: $(this).data('icon'),
                scaledSize: { width: 35, height: 35, f: 'px', b: 'px' },
            }
        : null;

    service.nearbySearch(request, callbackSearchNear);
    // var name = $(this).data('value');
    // var latitude = $(this).parent('.map-utilities__list').siblings('input[name="latitude"]').val();
    // var longtitude = $(this).parent('.map-utilities__list').siblings('input[name="longtitude"]').val();
    // var mapApi = $(this).parent('.map-utilities__list').siblings('input[name="map-api"]').val();
    // if (!latitude || !longtitude || !mapApi) return

    // let address = $(this).parent('.map-utilities__list').siblings('input[name="full_address"]').val(),
    //     link = $(this).hasClass('active')
    //         ? `https://www.google.com/maps/embed/v1/search?key=${mapApi}&zoom=15&center=${latitude},${longtitude}&q=${name}+gần+${address}`
    //         : `https://www.google.com/maps/embed/v1/place?key=${mapApi}&zoom=15&q=${latitude},${longtitude}`

    // $(this).parent('.map-utilities__list').siblings('.mapparent').attr('src', link);
});

$('body').on('click', '.popup-view-map__box .map-utilities__list.view-map__popup .map-utilities__item', async function () {
    if (!__viewMaps.map || __viewMaps.loadingUtilities) return

    __viewMaps.loadingUtilities = true
    $('.popup-view-map__load-utilities').addClass('loading')

    $(this).toggleClass('active');
    $(this).siblings().removeClass('active')

    let types = $(this).data('types') ? $(this).data('types').split(',') : null;

    hideMarkers(__viewMaps.utilitiesMarkers)
    __viewMaps.map.setCenter(__viewMaps.mapOptions.center)

    if (!types || !$(this).hasClass('active')) {
        __viewMaps.map.setZoom(__mapZoom)
        __viewMaps.loadingUtilities = false
        $('.popup-view-map__load-utilities').removeClass('loading')
        return
    }

    let service = new google.maps.places.PlacesService(__viewMaps.map),
        request = {
            location: {
                lat: __viewMaps.mapOptions.lat,
                lng: __viewMaps.mapOptions.lng
            },
            radius: 5000,
            types: types
        };

    __viewMaps.utilitiesMarkerIcon = $(this).data('icon')
        ?   {
                url: $(this).data('icon'),
                scaledSize: { width: 35, height: 35, f: 'px', b: 'px' },
            }
        : null;

    await service.nearbySearch(request, callbackViewMapSearchNear);
});

function getLocation () {
    return new Promise(function (resolve, reject) {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function (location) {
                const coordinates = {lat: location.coords.latitude, long: location.coords.longitude};
                resolve(coordinates);
            }, function () {
                reject('Location not found!');
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
 * @param canDrag = true
 * @returns {{}}
 */
function placeMarker(location, label, canDrag = true) {
    if (!map) return
    if (marker) {
        marker.setPosition(location);
    } else {
        addMarker(location, canDrag)
    }

    updateLatLng(location)
    map.setCenter(location);
    makeInfoWindowEvent(marker, label)
}

/**
 * Place Marker
 * @param location
 * @param label
 * @param canDrag = true
 * @returns {{}}
 */
function placeViewMapMarker(location, label, canDrag = true) {
    if (__viewMaps.marker) {
        __viewMaps.marker.setPosition(location);
    } else {
        addViewMapMarker(location, canDrag)
    }

    // updateLatLng(location)
    __viewMaps.map.setCenter(location);
    makeInfoWindowEvent(__viewMaps.marker, label)
}

/**
 * Add Marker to map
 * @param location
 * @param canDrag = true
 * @returns {{}}
 */
function addMarker(location, canDrag = true) {
    marker = new google.maps.Marker({
        position: location,
        draggable: canDrag ? true : false,
        map,
        icon: __markerIcon,
    });

    if (canDrag)
        marker.addListener('dragend', (event) => {
            markerToAddress({
                    location: event.latLng
                })
                .then(r => callbackMarkerToAddress(r))
        });

    return marker;
}

/**
 * Add Marker to map
 * @param location
 * @param canDrag = true
 * @returns {{}}
 */
function addViewMapMarker(location, canDrag = true) {
    __viewMaps.marker = new google.maps.Marker({
        position: location,
        draggable: false,
        map: __viewMaps.map,
        icon: __viewMaps.markerIcon,
    });

    return __viewMaps.marker;
}

/**
 * Geo code by request
 * @param request
 * @returns {{}}
 */
function geocode(request) {
    geocoder
        .geocode(request)
        .then((result) => {
            const {results} = result;
            placeMarker(results[0].geometry.location, results[0].formatted_address)
            return results[0];
        })
        .catch((e) => {
            console.log("Định vị không thành công vì lý do: " + e);
            // alert("Geocode was not successful for the following reason: " + e);
        });
}

/**
 * Counter
 * @param array
 * @returns {{}}
 */
function counter(array) {
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
        formatted_address = component.formatted_address,
        suspicious = [];

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
        if (isExist == false) suspicious.push(text);
    }

    return suspicious;
}

function suspiciousCombine(results) {
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
    let toDimensionArray = [].concat(...componentNames);

    return getMaxFromObject(counter(toDimensionArray));
}

/**
 * Geocode from lat & long to address
 * @param request
 * return {address, {lat,lng}}
 */
function markerToAddress(request) {
    let country_name = null,
        province_name = null,
        district_name = null,
        road_name = null,
        street_number = null,
        ward_name = null,
        formatted_address = null,
        latLng = request.location.toJSON(),
        shortAddress = null;

    if (!geocoder)
        geocoder = new google.maps.Geocoder();

    return geocoder.geocode(request)
        .then(result => {
            const {results} = result;
            formatted_address = results[0].formatted_address

            if (__latEle && __longEle)
                placeMarker(results[0].geometry.location, results[0].formatted_address)

            address = results[0]
            ward_name = suspiciousCombine(results);
            // Nếu tên phường có số
            let num;
            num = ward_name.search(/\p{L}\s\d+/gu) > 0 ? num = ward_name.indexOf(ward_name.match(/\d+/)[0]) : -1;
            ward_name = ward_name.toLowerCase().indexOf('phường') === 0 && num === 7 ? ward_name.match(/\d+/)[0] : ward_name;
            if (parseInt(ward_name) < 10) ward_name = '0' + ward_name;

            address.address_components.forEach(item => {
                item.types.forEach(type => {
                    switch (type) {
                        case "country":
                            country_name = item.long_name;
                            break
                        case "administrative_area_level_1":
                            province_name = item.long_name;
                            for (let key of __replaceToEmptyMaps) {
                                province_name = province_name.replace(key, "").trim()
                            }
                            break;
                        case "administrative_area_level_2":
                            district_name = item.long_name;
                            for (let key of __replaceToEmptyMaps) {
                                district_name = district_name.replace(key, "").trim()
                            }
                            break;
                        // Huyện cấp địa phương
                        case "locality":
                            district_name = item.long_name;
                            for (let key of __replaceToEmptyMaps) {
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

            let shortAddressArr = [
                street_number, road_name, ward_name
            ];

            shortAddress = shortAddressArr.filter(addressItem => {
                return !!addressItem;
            }).join(' ')

            return {
                country_name,
                province_name,
                district_name,
                ward_name,
                road_name,
                street_number,
                formatted_address,
                latLng,
                address: shortAddress,
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
        infoWindow.open(map, this);
    });

    // // mouseover
    // marker.addListener('mouseout', function() {
    //     infoWindow.close();
    // });
}

/**
 * Make popup detail of marker
 * @param marker
 * @param contentString
 */
function makeViewMapInfoWindowEvent(marker, contentString) {
    if (infoWindow) infoWindow.close(__viewMaps.map, marker);

    google.maps.event.addListener(marker, 'click', function () {
        infoWindow.setContent(contentString);
        infoWindow.open(__viewMaps.map, this);
    });

    // // mouseover
    // marker.addListener('mouseout', function() {
    //     infoWindow.close();
    // });
}

/**
 * Update lat lng into input
 * @param location
 */
function updateLatLng(location) {
    let latLng;

    try {
        latLng = location.toJSON();
    } catch (e) {
        latLng = location;
    }
    $(__latEle).val(latLng.lat)
    $(__longEle).val(latLng.lng)
}

/**
 * Find my location
 */
function addFindMyLocation() {
    var controlDiv = document.createElement('div');

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
                    markerToAddress({location: loc}).then((r) => callbackMarkerToAddress(r));
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

const callbackMarkerToAddress = async (result) => {
    if (!result || !__$mapParents || !__$mapParents.length) return;

    __isClickMarker = true
    __$mapParents.find('[name="address"]').val(result.address).trigger('change')

    await getProvinceFromName(result.province_name, __$mapParents.find('.province-load-district'))
    await sleep(500)
    await getDistrictFromName(result.district_name, __$mapParents.find('.district-province'))
    await  sleep(500)

    __isClickMarker = false
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

    // should add notification to accept location
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

/**
 * create marker
 * @param {*} place
 * @param canDrag = true
 * @param icon = null
 */
function createMarker(place, canDrag = true, icon = null) {
    marker = new google.maps.Marker({
        position: place.geometry.location,
        draggable: canDrag ? true : false,
        map,
        icon: icon || __markerIcon,
    });

    if (canDrag)
        marker.addListener('dragend', (event) => {
            markerToAddress({
                    location: event.latLng
                })
                .then(r => callbackMarkerToAddress(r))
        });

    return marker
}

/**
 * create marker
 * @param {*} place
 * @param canDrag = true
 * @param icon = null
 */
function createViewMapMarker(place, canDrag = true, icon = null) {
    __viewMaps.marker = new google.maps.Marker({
        position: place.geometry.location,
        draggable: canDrag ? true : false,
        map: __viewMaps.map,
        icon: icon || __markerIcon,
    });

    return __viewMaps.marker
}

$(function () {
    $('.js-change-address-load-map input[name=address], .js-change-address-load-map [name="province"], .js-change-address-load-map [name="district"]').on('change', function () {
        if (__isClickMarker) return

        google.maps.event.trigger(map, 'resize');

        let $provinceEl = $('.js-change-address-load-map [name="province"]'),
            $districtEl = $('.js-change-address-load-map [name="district"]'),
            $addressEl = $('.js-change-address-load-map input[name=address]'),
            provinceName = $provinceEl.val() ? $provinceEl.find('option:selected').text() : null,
            districtName = $districtEl.val() ? $districtEl.find('option:selected').text() : null,
            address = $addressEl.val(),
            fullAddress = [address, districtName, provinceName].filter(addressItem => {
                    return !!addressItem;
                }).join(', '),
            request = {
                query: fullAddress,
                fields: ['name', 'geometry'],
            },
            service = new google.maps.places.PlacesService(map);

        service.findPlaceFromQuery(request, function (results, status) {
            if (status === google.maps.places.PlacesServiceStatus.OK) {
                marker ? marker.setMap(null) : null;
                createMarker(results[0]);
                makeInfoWindowEvent(marker, fullAddress)

                $('.js-change-address-load-map input[name=latitude]').val(results[0].geometry.location.lat());
                $('.js-change-address-load-map input[name=longtitude]').val(results[0].geometry.location.lng());
                map.setCenter(results[0].geometry.location);
            }
        });
    });
});
