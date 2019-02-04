/**
* Theme: Minton Admin Template
* Author: Coderthemes
* Component: Sparkline Chart
*
*/
!function($) {
    "use strict";

    var Map = function() {};

    Map.prototype.init = function() {

        /* ----- REACTJS ----- */

        /* Region Table */
        $(document).ready(async function() {

            $('#map-markers').vectorMap({
                map: 'north_america_mill',
                normalizeFunction : 'polynomial',
                hoverOpacity : 0.7,
                hoverColor : false,
                regionStyle : {
                    initial : {
                        fill : '#4c5667'
                    }
                },
                markerStyle: {
                    initial: {
                        r: 9,
                        'fill': '#003fa4',
                        'fill-opacity': 1,
                        'stroke': '#fff',
                        'stroke-width' : 7,
                        'stroke-opacity': 0.4
                    },
                    hover: {
                        'stroke': '#bbb',
                        'fill-opacity': 1,
                        'stroke-width': 7,
                    }
                },
                markers: await $.SocketSDK.getMarkers(),
                backgroundColor : 'transparent',
                onMarkerTipShow: async function(event, label, index) {
                    try {
                        label.html(
                            '<b>Station: </b>'+ index +'<br/>'+
                            '<b>Temperature: </b></br>'+
                            '<b>Humidity: </b>'
                        );

                        let sation = await $.SocketSDK.getStation(index);

                        label.html(
                            '<b>Station: </b>'+ index +'<br/>'+
                            '<b>Temperature: </b>'+ sation.temperature +'°</br>'+
                            '<b>Humidity: </b>'+ sation.humidity +'%'
                        );
                    } catch (e) {
                        label.html(
                            '<b>Station: </b>'+ index +'<br/>'+
                            '<b style="color: red;">' + e.message + '</b>'
                        );
                    }
                }
            });

        });

    },
    //init
    $.Map = new Map, $.Map.Constructor = Map
}(window.jQuery),

//initializing
function($) {
    "use strict";
    $.Map.init();
}(window.jQuery);