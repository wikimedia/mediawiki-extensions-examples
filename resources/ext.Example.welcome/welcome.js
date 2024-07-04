/**
 * Display a welcome message on the page.
 *
 * This file is part of the 'ext.Example.welcome' module.
 *
 * It is enqueued for loading on all pages of the wiki,
 * from onBeforePageDisplay() in includes/Hooks.php.
 */

/**
 * Map from Date#getDay number to weekday localisation message key.
 *
 * TODO (README.md): This shows the wrong day. Why?
 *
 * @see https://developer.mozilla.org/en/JavaScript/Reference/Global_Objects/Date/getDay
 */
const DAY_MAP = [ 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday' ];

const welcome = {
	init: function () {
		// Use the current date
		const $box = welcome.createBox( mw.user.isNamed() ? mw.user.getName() : null, new Date() );

		// Ask jQuery to invoke this callback function once the document is ready.
		// See also <https://api.jquery.com/jQuery>.
		$( () => {
			$( 'h1' ).first().after( $box );
		} );
	},

	/**
	 * @param {string|null} userName Name of registered and logged-in user, or null if logged-out
	 * @param {Date} date
	 * @return {jQuery}
	 */
	createBox: function ( userName, date ) {
		// Get theassociated color for the date's day
		const dayKey = DAY_MAP[ date.getDay() ];
		const color = welcome.getColorByDate( dayKey );

		const $box = $( '<div class="mw-welcome-bar"></div>' ).text(
			!userName ?
				mw.msg( 'example-welcome-title-loggedout', mw.msg( dayKey ) ) :
				mw.msg( 'example-welcome-title-user', userName, mw.msg( dayKey ) )
		);

		// Append the message about today's color, and the color icon itself.
		$box
			.css( 'border-color', color )
			.attr( 'data-welcome-color', color );

		return $box;
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
		const colors = mw.config.get( 'wgExampleWelcomeColorDays' );

		if ( Object.hasOwnProperty.call( colors, dayKey ) ) {
			return colors[ dayKey ];
		}

		return mw.config.get( 'wgExampleWelcomeColorDefault' );
	}
};

if ( !window.QUnit ) {
	welcome.init();
}

module.exports = welcome;
mw.ExampleWelcome = welcome;
