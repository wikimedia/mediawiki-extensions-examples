/**
 * JavaScript for Welcome in Example.
 * Inserts a welcome message into the page content.
 */
( function ( mw, $ ) {
	var welcome, dayMap;

	/**
	 * Local cache of mapping date day indices to day names in English.
	 * Note that the range is 0-6, where 0 = Sunday.
	 * See also https://developer.mozilla.org/en/JavaScript/Reference/Global_Objects/Date/getDay
	 */
	dayMap = [ 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday' ];

	welcome = {
		init: function () {
			var $box, dNow, color;

			$box = $( '<div class="mw-example-welcome"></div>' ).append(
				$( '<h4>', {
					text: mw.user.isAnon() ?
						mw.msg( 'example-welcome-title-loggedout' ) :
						mw.msg( 'example-welcome-title-user', mw.user.getName() )
				} )
			);

			// Get the current date and the associated color for that date's day
			dNow = new Date();
			color = welcome.getColorByDate( dNow );

			// Append the message about today's color, and the color icon itself.
			$box.prepend(
				$( '<div>' )
					.addClass( 'mw-example-welcome-color' )
					.css( 'borderColor', color )
					.prepend(
						$( '<h5>' ).text( mw.msg( 'example-welcome-color-label' ) )
					)
					.append(
						$( '<code>' )
							.addClass( 'mw-example-welcome-color-code' )
							.text( mw.msg( 'example-welcome-color-value', color ) )
					)
			);

			$( '#content' ).append( $box );
		},

		/**
		 * Get the color associated with the given date's day.
		 * If no color is assigned to this day, the default will be used instead.
		 *
		 * @param {Date} d Instance of Date to use to get the correct color.
		 *
		 * @return {string} wgExampleWelcomeColorDefault Global variable
		 */
		getColorByDate: function ( d ) {
			var days, day;

			day = dayMap[ d.getDay() ];
			days = mw.config.get( 'wgExampleWelcomeColorDays' );

			if ( day && days[ day ] !== undefined ) {
				return days[ day ];
			}

			return mw.config.get( 'wgExampleWelcomeColorDefault' );
		}
	};

	mw.libs.welcome = welcome;

}( mediaWiki, jQuery ) );
