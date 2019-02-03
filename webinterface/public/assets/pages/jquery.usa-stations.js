/**
* Theme: Minton Admin Template
* Author: Coderthemes
* Component: Sparkline Chart
*
*/
!function($) {
    "use strict";

    var USAStations = function() {};

    USAStations.prototype.init = function() {

        /* ----- REACTJS ----- */

        /* Region Table */
        let StationsSubject = new rxjs.Subject();

        ReactDOM.render(
            <StationTable
                stations={ StationsSubject }
            />,
            document.getElementById('station-table')
        );

        USAStations.UpdateRegionTable = async function () {
            StationsSubject.next(await $.SocketSDK.getUSAStations())
        };

        USAStations.UpdateRegionTable();

    },
    //init
    $.USAStations = new USAStations, $.USAStations.Constructor = USAStations
}(window.jQuery),

//initializing
function($) {
    "use strict";
    $.USAStations.init();
}(window.jQuery);