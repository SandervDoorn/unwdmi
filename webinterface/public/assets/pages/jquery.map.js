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

        $(document).ready(function() {
            $('#honduras-map-markers').vectorMap({
                map: 'honduras',
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
                        'fill': '#a288d5',
                        'fill-opacity': 0.9,
                        'stroke': '#fff',
                        'stroke-width' : 7,
                        'stroke-opacity': 0.4
                    },
                    hover: {
                        'stroke': '#fff',
                        'fill-opacity': 1,
                        'stroke-width': 1.5
                    }
                },
                backgroundColor : 'transparent',
                onMarkerTipShow: function(event, label, index){
                    label.html(
                        '<b>'+ index +'</b><br/>'+
                        '<b>Population: </b>'+ index +'</br>'+
                        '<b>Unemployment rate: </b>'+ index +'%'
                    );
                },
                onRegionTipShow: function(event, label, code){
                    label.html(
                        '<b>'+label.html()+'</b></br>'+
                        'Average Temperature: 17 &deg;'
                    );
                }
            });
        })

    },
    //init
    $.Map = new Map, $.Map.Constructor = Map
}(window.jQuery),

//initializing
function($) {
    "use strict";
    $.Map.init();
}(window.jQuery);