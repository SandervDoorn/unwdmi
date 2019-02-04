/**
* Theme: Minton Admin Template
* Author: Coderthemes
* Component: Sparkline Chart
*
*/
!function($) {
    "use strict";

    var Dashboard = function() {};

    Dashboard.MeasureDelayTime = 10000;

    Dashboard.prototype.init = function()
    {

        $(document).ready(async function() {
            console.log('test');

            let hondurasMarkers = await $.SocketSDK.getHondurasMarkers();

            let lngLeft = -89.35;
            let lngRight = -83.13;
            let latTop = 16.5;
            let latBottom = 13;

            let coordRight = 1000;
            let coordTop = 150;
            let coordBottom = 735;

            console.log(hondurasMarkers);

            let markers = {};
            $.map(hondurasMarkers, function(value, key) {
                let lat = value.latLng[0];
                let lng = value.latLng[1];

                let latCoord = lat - latBottom;
                let latScale = latCoord / (latTop - latBottom);

                let lngCoord = lng - lngRight;
                let lngScale = lngCoord / (lngLeft - lngRight);

                let coord = [
                    coordRight - (lngScale * coordRight),
                    ((coordBottom - coordTop) - (latScale * (coordBottom - coordTop))) + coordTop
                ];

                let marker = {
                    coords: coord,
                    name: value.name
                };

                markers[key] = marker;
            });

            console.log(markers);

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
                markers: markers,
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

            /* ----- REACTJS ----- */

            /* Cards */
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

            Dashboard.UpdateCards = async function() {
                try {
                    Temperature.next(await $.SocketSDK.getAverageTemperature());
                    Humidity.next(await $.SocketSDK.getAverageHumidity());
                    MeasureDealyTime.next(Dashboard.MeasureDelayTime/1000);
                } catch (e) {
                    console.error(e.message);
                    Temperature.next('-');
                    Humidity.next('-');
                    MeasureDealyTime.next('-');
                }
            };

            /* Region Table */
            let Regions = new rxjs.Subject();

            ReactDOM.render(
                <RegionTable
                    regions={ Regions }
                />,
                document.getElementById('region-table')
            );

            Dashboard.UpdateRegionTable = async function () {
                try {
                    Regions.next(await $.SocketSDK.getHondurasRegions())
                } catch (e) {
                    console.error(e.message);
                    Regions.next([]);
                }
            };

            Dashboard.UpdateCards();
            Dashboard.UpdateRegionTable();

            setInterval(Dashboard.UpdateCards, Dashboard.MeasureDelayTime);
            setInterval(Dashboard.UpdateRegionTable, Dashboard.MeasureDelayTime);
        });
    },

    Dashboard.prototype.setMeasureDelayTime = function(measureDelayTime)
    {
        Dashboard.MeasureDelayTime = measureDelayTime;
    };

    //init
    $.Dashboard = new Dashboard, $.Dashboard.Constructor = Dashboard
}(window.jQuery),


//initializing
function($) {
    "use strict";
    $.Dashboard.setMeasureDelayTime(10000);
    $.Dashboard.init();
}(window.jQuery);