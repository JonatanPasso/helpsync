import Util from "../util";

export default {

    bind(el, binding, vnode) {
        var latLng = binding.value;

        if (!window['geo_resolv']) {

            window.geo_resolv = function (latLng, cb) {

                let key = `s${latLng[0]},${latLng[1]}`;

                if (!window['geo_queue']) {
                    window['geo_queue'] = [];
                }
                if (!window['geo_cache']) {
                    window['geo_cache'] = [];
                }

                if (geo_cache[key]) {
                    cb(geo_cache[key]);
                    return;
                }

                geo_queue.push([latLng, cb]);

                if (!window['geo_find']) {
                    window['geo_find'] =
                        setInterval(() => {

                            if (geo_queue.length == 0) return;

                            var itemFila = geo_queue.shift();

                            let key = `s${itemFila[0][0]},${itemFila[0][1]}`

                            if (geo_cache[key]) {

                                itemFila[1].call(this, [geo_cache[key]]);
                                return;
                            }

                            vnode.context
                                .doGet('frota/logs/geocode', {
                                    lat: itemFila[0][0],
                                    lng: itemFila[0][1],
                                }, false)
                                .then(r => {
                                    itemFila[1].call(this, [r.formated_address]);
                                    geo_cache[key] = r.formated_address;
                                })
                                .fail(e => {
                                    if (_.get(e, 'status') == 404) {
                                        Util.googleMaps
                                            .reverseGeoCode(itemFila[0][0],
                                                itemFila[0][1])
                                            .then(function (result) {
                                                if (result.status == 'OK') {
                                                    var reverse_geocode = result.results[0].formatted_address;
                                                    itemFila[1].call(this, [reverse_geocode]);
                                                    geo_cache[key] = reverse_geocode;

                                                    vnode.context
                                                        .doPost('frota/logs/geocode-save', {
                                                            formated_address: reverse_geocode,
                                                            lat: itemFila[0][0],
                                                            lng: itemFila[0][1],
                                                            data: result.results[0]
                                                        });

                                                }
                                            });
                                    }
                                });

                            return;


                        }, 1000);
                }

            }

        }

        el.innerHTML = 'CARREGANDO...';

        geo_resolv(latLng, e => {
            el.innerHTML = e;
        });
    }
}
