var Util = {};

/**
 * Check if current browser have fullscreen support
 * @returns {boolean}
 */
Util.isFullscreenSupported = function () {

    var supported = false;

    if ('requestFullscreen' in document.body) supported = true;
    if ('msRequestFullscreen' in document.body) supported = true;
    if ('mozRequestFullScreen' in document.body) supported = true;
    if ('webkitRequestFullscreen' in document.body) supported = true;

    return supported;
}

/**
 * Request fullscreen form current document or html element
 * @param HtmlElement element Default body
 */
Util.toggleFullscreen = function (element) {

    var doc = element ? element : document.documentElement;

    if (!document.fullscreenElement &&    // alternative standard method
        !document.mozFullScreenElement && !document.webkitFullscreenElement) {  // current working methods
        if (document.documentElement.requestFullscreen) {
            doc.requestFullscreen();
        } else if (document.documentElement.mozRequestFullScreen) {
            doc.mozRequestFullScreen();
        } else if (document.documentElement.webkitRequestFullscreen) {
            doc.webkitRequestFullscreen(Element.ALLOW_KEYBOARD_INPUT);
        }
    } else {
        if (document.cancelFullScreen) {
            document.cancelFullScreen();
        } else if (document.mozCancelFullScreen) {
            document.mozCancelFullScreen();
        } else if (document.webkitCancelFullScreen) {
            document.webkitCancelFullScreen();
        }
    }

}


/**
 * Request fullscreen form current document or html element
 * @param HtmlElement element Default body
 */
Util.isFullscreen = function (element) {


    if (!document.fullscreenElement &&    // alternative standard method
        !document.mozFullScreenElement && !document.webkitFullscreenElement) {  // current working methods
        return false;
    } else {
        return true;
    }

}

Util.uid = function () {
    function s4() {
        return Math.floor((1 + Math.random()) * 0x10000)
            .toString(16)
            .substring(1);
    }

    return s4() + s4() + '-' + s4() + '-' + s4() + '-' + s4() + '-' + s4() + s4() + s4();

}

/**
 * Pacoate funcoes uteis para manipulacao mapas
 * @type {{}}
 */
Util.Leaflet = {};

Util.Leaflet.initMap = function (id, zoom) {

    var map = new L.Map(id, {center: new L.LatLng(-14, -50), zoom: zoom ? zoom : 5, editable: true});

    var roads = L.gridLayer.googleMutant({
        type: 'roadmap' // valid values are 'roadmap', 'satellite', 'terrain' and 'hybrid'
    }).addTo(map);

    var satellite = L.gridLayer.googleMutant({
        type: 'satellite' // valid values are 'roadmap', 'satellite', 'terrain' and 'hybrid'
    });

    L.control.layers({
        satellite: satellite,
        Roadmap: roads
    }, {}, {
        collapsed: false
    }).addTo(map);

    return map;
};

Util.Leaflet.iconePosicao = function (posicao) {

    var template = 'Data: <b><%= data_hora %></b><br>';
    template += 'Ignição: <b><%= ignicao %></b><br>';
    template += 'Velocidade: <b><%= vel %> Km/h</b><br>';

    var aux = _.template(template)({
        ignicao: posicao.engine_ignition == 1 ? 'Ligado' : 'Desligado',
        data_hora: Vue.filter('unixdate')(posicao.gps_timestamp),
        vel: Vue.filter('mask_dinheiro')(posicao.gps_speed, 2),
    });

    return L.circleMarker([posicao.gps_lat, posicao.gps_lng], {radius: 7})
        .bindPopup(aux);
}

Util.Leaflet.iconeVeiculo = function (desc, posicao) {

    var myIcon = L.divIcon({
        iconAnchor: [32, 79],
        className: 'veiculo',
        html: '<div><img class="mapa-carro-icone" ' +
            'src="/img/rastreio/icones/frotas-car-on.png"></div>'
    });

    var location = [posicao.gps_lat, posicao.gps_lng];

    var divIcon = L.divIcon({
        iconAnchor: [-33, 60],
        className: 'custon-icon',
        html: '<div class="map-p-v">' + desc + '</div>'
    });

    var markerOptions = {
        title: desc,
        icon: myIcon
    };

    var marker = L.marker(location, markerOptions);


    marker.on('add', function (ev) {

        marker.iconePlaca = L.marker(location, {icon: divIcon});
        marker.iconePlaca.addTo(this._map);

    });

    marker.on('remove', function (ev) {

        try {

            if (this.iconePlaca) {
                this._map.removeLayer(this.iconePlaca);
                this.iconePlaca = null;
            }

        } catch (e) {

        }

    });


    return marker;
}


Util.googleMaps = {
    reverseGeoCode: function (lat, lng) {

        var templaetUrl = "https://maps.googleapis.com/maps/api/geocode/json?latlng={lat},{lng}&key={API_KEY}";

        var params = {
            API_KEY: GOOGLE_MAPS_KEY,
            lat: lat,
            lng: lng
        }

        _.each(params, function (value, key) {
            templaetUrl = templaetUrl.replace('{' + key + '}', value);
        });

        return $.get(templaetUrl);


    }
}

Util.getRandomColor = function () {
    var letters = '0123456789ABCDEF';
    var color = '#';
    for (var i = 0; i < 6; i++) {
        color += letters[Math.floor(Math.random() * 16)];
    }
    return color;
}

export default Util;
