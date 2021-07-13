'use strict';

const { assert, REST } = require( 'api-testing' );

describe( 'Rest Api Example', () => {
	const client = new REST( 'rest.php/examples/v1' );

	describe( 'GET /echo_path_param/{value_to_echo}', () => {
		it( 'Should successfully return a response when provided both parameters', async () => {
			const { status, body } = await client.get( '/echo_path_param/hello?text_action=reverse' );
			assert.deepEqual( status, 200 );
			assert.deepEqual( body, { echo: 'olleh' } );
		} );
		it( 'Should successfully return a response when not provided with text_action', async () => {
			const { status, body } = await client.get( '/echo_path_param/hello' );
			assert.deepEqual( status, 200 );
			assert.deepEqual( body, { echo: 'hello' } );
		} );
		it( 'Should return 400 error when provided with an invalid text_action', async () => {
			const { status } = await client.get( '/echo_path_param/hello?text_action=foobar' );
			assert.deepEqual( status, 400 );
		} );
		it( 'Should return 400 error when not provided any parameters', async () => {
			const { status } = await client.get( '/echo_path_param/' );
			assert.deepEqual( status, 400 );
		} );
	} );
} );
