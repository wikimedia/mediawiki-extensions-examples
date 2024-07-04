QUnit.module( 'ext.Example.welcome', QUnit.newMwEnvironment(), function ( hooks ) {

	hooks.beforeEach( function () {
		mw.config.set( {
			wgExampleWelcomeColorDays: {
				tuesday: 'pink'
			},
			wgExampleWelcomeColorDefault: '#ccc'
		} );
	} );

	QUnit.test( 'getColorByDate', function ( assert ) {
		var welcome = require( 'ext.Example.welcome' );

		assert.strictEqual( welcome.getColorByDate( 'monday' ), '#ccc', 'monday default' );
		assert.strictEqual( welcome.getColorByDate( 'tuesday' ), 'pink', 'tuesday custom' );
	} );

	QUnit.test( 'createBox [logged-in]', function ( assert ) {
		var welcome = require( 'ext.Example.welcome' );
		var date = new Date( '2011-04-01T12:00:00Z' );
		var $box = welcome.createBox( 'Alice', date );
		var actual = $box.text();

		// TODO (README.md): Make this test more strict
		// https://timotijhof.net/posts/2015/qunit-anti-patterns/
		assert.true( actual.includes( 'example-welcome-title-user' ) );
	} );

	QUnit.test( 'createBox [anon]', function ( assert ) {
		var welcome = require( 'ext.Example.welcome' );
		var date = new Date( '2011-04-01T12:00:00Z' );
		var $box = welcome.createBox( null, date );
		var actual = $box.text();

		assert.true( actual.includes( 'example-welcome-title-loggedout' ) );
	} );

} );
