/**
* Theme: Minton Admin Template
* Author: Coderthemes
* Component: Sparkline Chart
*
*/
!function($) {
    "use strict";

    var Dashboard = function() {};

    //creates Donut chart
    Dashboard.prototype.createDonutChart = function(element, data, colors) {
        Morris.Donut({
            element: element,
            data: data,
            resize: true, //defaulted to true
            colors: colors
        });
    },

    Dashboard.prototype.init = function() {
        //creating donut chart
        var $donutData = [
                {label: "Download Sales", value: 12},
                {label: "In-Store Sales", value: 30},
                {label: "Mail-Order Sales", value: 20}
            ];
        this.createDonutChart('morris-donut-example', $donutData, ['#f1b53d', '#ebeff2','#03a9f3']);

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

            $('#card-temperature').click(function () {
                let _self = this;
                console.log(_self);
                swal({
                    title: 'Temperature',
                    type: 'info',
                    html: '<p style="border-bottom: 1px solid #eee; padding-bottom: 15px">The temperature given in this card is the average temperature of all stations together. The stations who are used in this calculation can be found on the "Stations" page in the side-menu or click <a href="/stations">here</a>.</p><div class="widget-bg-color-icon">\n' +
                    '                <div class="bg-icon bg-icon-success pull-left">\n' +
                    '                    <i class="wi wi-thermometer text-success"></i>\n' +
                    '                </div>\n' +
                    '                <div class="text-right">\n' +
                    '                    <h3 class="text-dark p-t-10"><b class="counter">' + $(_self).find('.counter').text() + '</b> <i class="wi wi-celsius"></i></h3>\n' +
                    '                    <p class="text-muted mb-0">Temperature average</p>\n' +
                    '                </div>\n' +
                    '                <div class="clearfix"></div>\n' +
                    '            </div>',
                    showCloseButton: true,
                    showConfirmButton: false
                })
            });

            $('#card-humidity').click(function () {
                let _self = this;
                console.log(_self);
                swal({
                    title: 'Humidity',
                    type: 'info',
                    html: '<p style="border-bottom: 1px solid #eee; padding-bottom: 15px">The humidity given in this card is the average humidity of all stations together. The stations who are used in this calculation can be found on the "Stations" page in the side-menu or click <a href="/stations">here</a>.</p><div class="widget-bg-color-icon">\n' +
                    '                <div class="bg-icon bg-icon-primary pull-left">\n' +
                    '                    <i class="wi wi-humidity text-info"></i>\n' +
                    '                </div>\n' +
                    '                <div class="text-right">\n' +
                    '                    <h3 class="text-dark p-t-10"><b class="counter">' + $(_self).find('.counter').text() + '</b> &percnt;</h3>\n' +
                    '                    <p class="text-muted mb-0">Humidity</p>\n' +
                    '                </div>\n' +
                    '                <div class="clearfix"></div>\n' +
                    '            </div>',
                    showCloseButton: true,
                    showConfirmButton: false
                })
            });

            $('#card-measure-delay').click(function () {
                let _self = this;
                console.log(_self);
                swal({
                    title: 'Measure delay time',
                    type: 'info',
                    html: '<p style="border-bottom: 1px solid #eee; padding-bottom: 15px">The measure delay time is the delay time the application uses for updating the real-time widgets. That means that if 10 is the delay time, every 10 seconds the applications receives new data.</p><div class="widget-bg-color-icon">\n' +
                    '                <div class="bg-icon bg-icon-danger pull-left">\n' +
                    '                    <i class="mdi mdi-av-timer text-info"></i>\n' +
                    '                </div>\n' +
                    '                <div class="text-right">\n' +
                    '                    <h3 class="text-dark p-t-10"><b class="counter">' + $(_self).find('.counter').text() + '</b> <i class="wi wi-refresh-alt"></i></h3>\n' +
                    '                    <p class="text-muted mb-0">Measure delay time</p>\n' +
                    '                </div>\n' +
                    '                <div class="clearfix"></div>\n' +
                    '            </div>',
                    showCloseButton: true,
                    showConfirmButton: false
                })
            });
        });
    },
    //init
    $.Dashboard = new Dashboard, $.Dashboard.Constructor = Dashboard
}(window.jQuery),

//initializing
function($) {
    "use strict";
    $.Dashboard.init();

    $(document).ready(function() {
        ReactDOM.render(
            <Card />,
            document.getElementById('temperature-card')
        );
    });
}(window.jQuery);