/**
 * Author: Redmar Bakker
 *
 */
!function($) {
    "use strict";

    var SocketSDK = function() {};
    SocketSDK.results = {};

    SocketSDK.prototype.init = function(port)
    {
        SocketSDK.socket = io('http://management.lacthosa.com:' + port);
        SocketSDK.socket.on('disconnect', function() {});
    };

    SocketSDK.prototype.request = function(command, param)
    {
        let requestID = Math.random();
        SocketSDK.socket.emit(command, [USER_TOKEN, requestID, param]);

        return new Promise(function(resolve, reject) {
            let done = false;
            let closure = function(data) {
                if (data.requestId == requestID) {
                    if (typeof data.error !== "undefined") {
                        done = true;
                        SocketSDK.socket.off('result', closure);

                        if (data.code == 403) {
                            swal({
                                title: 'Account logged in elsewhere!',
                                type: 'warning',
                                html: '<p>You will be forced to login in again.</p>',
                                showCloseButton: false,
                                showConfirmButton: false
                            });

                            setTimeout(() => {
                                window.location.replace('/auth/logout');
                            }, 5000)
                        }

                        reject(new Error(data.error));
                    }

                    done = true;
                    SocketSDK.socket.off('result', closure);
                    resolve(data.result);
                }
            };

            SocketSDK.socket.on('result', closure);

            setTimeout(function() {
                if (!done) {
                    reject(new Error('connect_timeout'));
                }
            }, 5000)
        });
    };

    SocketSDK.prototype.getStation = function (stationID)
    {
        return SocketSDK.prototype.request('get_station', arguments);
    };

    SocketSDK.prototype.getUSAMarkers = function ()
    {
        return SocketSDK.prototype.request('get_usa_markers');
    };

    SocketSDK.prototype.getHondurasMarkers = function ()
    {
        return SocketSDK.prototype.request('get_honduras_markers');
    };

    SocketSDK.prototype.getAverageTemperature = function ()
    {
        return SocketSDK.prototype.request('get_average_temperature');
    };

    SocketSDK.prototype.getAverageHumidity = function ()
    {
        return SocketSDK.prototype.request('get_average_humidity');
    };

    SocketSDK.prototype.getHondurasRegions = function ()
    {
        return SocketSDK.prototype.request('get_honduras_regions');
    };

    SocketSDK.prototype.getHondurasStations = function ()
    {
        return SocketSDK.prototype.request('get_honduras_stations');
    };

    SocketSDK.prototype.getUSAStations = function ()
    {
        return SocketSDK.prototype.request('get_usa_stations');
    };

    SocketSDK.prototype.getArchive = function ()
    {
        return SocketSDK.prototype.request('get_archive', arguments)
    };

    SocketSDK.prototype.getMarkers = function ()
    {
        return SocketSDK.prototype.request('get_markers', arguments)
    };

    //init
    $.SocketSDK = new SocketSDK, $.SocketSDK.Constructor = SocketSDK
}(window.jQuery),


//initializing
function($) {
    "use strict";
    $.SocketSDK.init(8000);
}(window.jQuery);