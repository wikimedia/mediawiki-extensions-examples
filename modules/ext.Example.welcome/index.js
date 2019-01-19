/**
 * Display a welcome message on the page.
 *
 * This file is part of the 'ext.Example.welcome' module.
 *
 * It is enqueued for loading on all pages of the wiki,
 * from ExampleHooks::onBeforePageDisplay() in Example.hooks.php.
 */
( function () {
	var welcome, dayMap;

	// Mapping of Date#getDay integer to week day message key.
	// The range is 0-6, where 0 = Sunday.
	// See also https://developer.mozilla.org/en/JavaScript/Reference/Global_Objects/Date/getDay
	dayMap = [ 'sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday' ];

	welcome = {
		init: function () {
			var $box, dayKey, color;

			// Get the current date and the associated color for that date's day
			dayKey = dayMap[ new Date().getDay() ];
			color = welcome.getColorByDate( dayKey );

			$box = $( '<div class="mw-welcome-bar"></div>' ).text(
				mw.user.isAnon() ?
					mw.msg( 'example-welcome-title-loggedout', mw.msg( dayKey ) ) :
					mw.msg( 'example-welcome-title-user', mw.user.getName(), mw.msg( dayKey ) )
			);

			// Append the message about today's color, and the color icon itself.
			$box
				.css( 'borderColor', color )
				.attr( 'data-welcome-color', color );

			// Ask jQuery to invoke this callback function once the page is ready.
			// See also <https://api.jquery.com/jQuery>.
			$( function () {
				$( 'h1' ).first()
					.after( $box );
			} );
		},

		/**
		 * Get the color associated with the given day.
		 *
		 * If no color is assigned to this day, the default will be used instead.
		 *
		 * @param {string} dayKey Weekday in English (lowercase).
		 * @return {string} CSS color value
		 */
		getColorByDate: function ( dayKey ) {
			var colors = mw.config.get( 'wgExampleWelcomeColorDays' );

			if ( Object.hasOwnProperty.call( colors, dayKey ) ) {
				return colors[ dayKey ];
			}

			return mw.config.get( 'wgExampleWelcomeColorDefault' );
		}
	};

	module.exports = welcome;

	mw.ExampleWelcome = welcome;
}() );
