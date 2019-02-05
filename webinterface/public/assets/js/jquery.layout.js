/**
* Theme: Minton Admin Template
* Author: Coderthemes
* Component: Sparkline Chart
*
*/
!function($) {
    "use strict";

    var Layout = function() {};

    Layout.prototype.init = function()
    {

        window.requests = new rxjs.Subject();

        ReactDOM.render(
            <SideBar />,
            document.getElementById('side-bar')
        );

    },

    //init
    $.Layout = new Layout, $.Layout.Constructor = Layout
}(window.jQuery),


//initializing
function($) {
    "use strict";
    $.Layout.init();

}(window.jQuery);