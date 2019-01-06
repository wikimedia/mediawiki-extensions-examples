/* eslint-env node */

global.mw = {
	config: {
		values: {},
		get: function ( key ) {
			return this.values[ key ];
		}
	}
};

// global.sinon = require( 'sinon' );
