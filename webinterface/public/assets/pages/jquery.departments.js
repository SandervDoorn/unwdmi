/**
* Theme: Minton Admin Template
* Author: Coderthemes
* Component: Sparkline Chart
*
*/
!function($) {
    "use strict";

    var Departments = function() {};

    Departments.prototype.init = function() {

        $(document).ready(function() {

            //Buttons examples
            var table = $('#datatable-buttons').DataTable({
                lengthChange: false,
                buttons: ['copy', 'excel', 'pdf']
            });

            table.buttons().container()
                .appendTo('#datatable-buttons_wrapper .col-md-6:eq(0)');
        } );

    },
    //init
    $.Departments = new Departments, $.Departments.Constructor = Departments
}(window.jQuery),

//initializing
function($) {
    "use strict";
    $.Departments.init();
}(window.jQuery);