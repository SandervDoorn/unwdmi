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

        $(document).ready(async function() {
            console.log(await $.SocketSDK.getHondurasMarkers());
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
                markers: await $.SocketSDK.getHondurasMarkers(),
                backgroundColor : 'transparent',
                onMarkerTipShow: async function(event, label, index) {
                    try {
                        label.html(
                            '<b>Station: </b>'+ index +'<br/>'+
                            '<b>Temperature: </b>...</br>'+
                            '<b>Humidity: </b>...%'
                        );

                        let sation = await $.SocketSDK.getStation(index);

                        label.html(
                            '<b>Station: </b>'+ index +'<br/>'+
                            '<b>Temperature: </b>'+ sation.temperature +'</br>'+
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

            $('#america-map-markers').vectorMap({
                map: 'us_aea_en',
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
                markers: await $.SocketSDK.getUSAMarkers(),
                backgroundColor : 'transparent',
                onMarkerTipShow: async function(event, label, index) {
                    try {
                        label.html(
                            '<b>Station: </b>'+ index +'<br/>'+
                            '<b>Temperature: </b>...</br>'+
                            '<b>Humidity: </b>...%'
                        );

                        let sation = await $.SocketSDK.getStation(index);

                        label.html(
                            '<b>Station: </b>'+ index +'<br/>'+
                            '<b>Temperature: </b>'+ sation.temperature +'</br>'+
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
    $.Dashboard = new Dashboard, $.Dashboard.Constructor = Dashboard
}(window.jQuery),


//initializing
function($) {
    "use strict";
    $.Dashboard.init();

    $(document).ready(function() {

        let Temperature = new rxjs.Subject();

        ReactDOM.render(
            <Card
                title="Temperature"
                description={(<p className="swal2-description">The temperature given in this card is the average temperature of all stations together. The stations who are used in this calculation can be found on the "Stations" page in the side-menu or click <a href="/stations">here</a>.</p>)}
                icon="wi wi-thermometer"
                iconType="success"
                value={ Temperature }
                valueType={(<i className="wi wi-celsius"/>)}
                valueDescription="Temperature average"
            />,
            document.getElementById('temperature-card')
        );

        let Humidity = new rxjs.Subject();

        ReactDOM.render(
            <Card
                title="Humidity"
                description={(<p className="swal2-description">The humidity given in this card is the average humidity of all stations together. The stations who are used in this calculation can be found on the "Stations" page in the side-menu or click <a href="/stations">here</a>.</p>)}
                icon="wi wi-humidity"
                iconType="info"
                value={ Humidity }
                valueType={(<span>%</span>)}
                valueDescription="Humidity"
            />,
            document.getElementById('humidity-card')
        );

        let MeasureDealyTime = new rxjs.Subject();

        ReactDOM.render(
            <Card
                title="Measure delay time"
                description={(<p className="swal2-description">The measure delay time is the delay time the application uses for updating the real-time widgets. That means that if 10 is the delay time, every 10 seconds the applications receives new data.</p>)}
                icon="mdi mdi-av-timer"
                iconType="danger"
                value={ MeasureDealyTime }
                valueType={(<i className="wi wi-refresh-alt"/>)}
                valueDescription="Measure delay time"
            />,
            document.getElementById('measure-delay-card')
        );

        let Update = async function() {
            Temperature.next(await $.SocketSDK.getAverageTemperature());
            Humidity.next(await $.SocketSDK.getAverageHumidity());
            MeasureDealyTime.next(3);
        };

        Update();
        setInterval(Update, 3000);
    });
}(window.jQuery);