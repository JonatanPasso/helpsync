<template>
    <div v-jsbarcode="produto.codigo_barras"></div>
</template>

<script>

import JsBarcode from 'jsbarcode'
import Util from '@/util'

/**
 *
 * CODE128
 * CODE128A
 * CODE128B
 * CODE128C
 * EAN13
 * EAN8
 * EAN5
 * EAN2
 * UPC
 * ITF14
 * ITF
 * MSI
 * MSI10
 * MSI11
 * MSI1010
 * MSI1110
 */

/**
 * MAPA OPCOES
 */
var MAPA = {
    'EAN': 'EAN13',
    // 'UPC': '',
    // 'GTIN': '',
    // 'ISBN': '',
    // 'JAN': ''
}

export default {

    name: "CodigoBarras",

    props: {
        produto: {
            type: Object,
            default() {
                return {
                    tipo_codigo_barras: '',
                    codigo_barras: ''
                }
            }
        }
    },

    watch: {
        'produto.codigo_barras'(prod) {

        }
    },

    directives: {

        jsbarcode: {

            inserted(element, bind, vnode) {

                element.renderChart = function (number) {

                    if (`${number}`.length < 13) {
                        $(element).empty();
                        return;
                    }


                    let uid = Util.uid();
                    element.canvas = $(element).html(`<canvas id="flux_${uid}"></canvas>`).get(0);

                    try {
                        JsBarcode(`#flux_${uid}`, number, {
                            format: "EAN13",
                            lineColor: "#000",
                            width: 2,
                            height: 80,
                            displayValue: true
                        });
                    } catch (e) {
                        $(element).html(e);
                    }
                }

                element.renderChart(bind.value);

                //7506306244177


            },
            update(element, bind, vnode) {

                element.renderChart(bind.value);
            }
        }
    }

}
</script>

<style scoped>

</style>
