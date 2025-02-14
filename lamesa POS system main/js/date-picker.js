
// date jquery library

    jQuery(function() {
        jQuery("#created_date, #updated_date").datepicker();

        jQuery("#created_today").click(function() {
            var today = new Date();
            jQuery("#created_date").datepicker("setDate", today);
        });

        jQuery("#updated_today").click(function() {
            var today = new Date();
            jQuery("#updated_date").datepicker("setDate", today);
        });
    });


    $(document).ready(function() {
        $(".datepicker").datepicker({
            dateFormat: 'yy-mm-dd'
        });
    });
