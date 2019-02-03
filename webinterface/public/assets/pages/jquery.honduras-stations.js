/**
* Theme: Minton Admin Template
* Author: Coderthemes
* Component: Sparkline Chart
*
*/
!function($) {
    "use strict";

    var HondurasStations = function() {};

    HondurasStations.prototype.init = function() {

        /* ----- REACTJS ----- */

        /* Region Table */
        let StationsSubject = new rxjs.Subject();

        ReactDOM.render(
            <StationTable
                stations={ StationsSubject }
            />,
            document.getElementById('station-table')
        );

        HondurasStations.UpdateRegionTable = async function () {
            StationsSubject.next(await $.SocketSDK.getHondurasStations())
        };

        HondurasStations.UpdateRegionTable();

    },
    //init
    $.HondurasStations = new HondurasStations, $.HondurasStations.Constructor = HondurasStations
}(window.jQuery),

//initializing
function($) {
    "use strict";
    $.HondurasStations.init();
}(window.jQuery);