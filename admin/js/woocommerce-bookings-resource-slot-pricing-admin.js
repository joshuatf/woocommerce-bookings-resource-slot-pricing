(function( $ ) {
    'use strict';
    
	$( document ).on( 'change', '.wc_booking_availability_type select, .wc_booking_pricing_type select', function() {
		var value = $(this).val();
		var tr    = $(this).closest('tr')
		var row   = $(tr);

		if ( value == 'time:weekday_month_range' ) {
            row.find('.from_date, .to_date').show();
			row.find('.from_day_of_week, .to_day_of_week').show();
        }
	});

})( jQuery );
