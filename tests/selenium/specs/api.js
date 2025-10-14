// this is just a sample on how to create a user using the API
// the test helps us know that the API functionality works in
// core when we release a new wdio-mediawiki package
import { createAccount, mwbot } from 'wdio-mediawiki/Api.js';
import { getTestString } from 'wdio-mediawiki/Util.js';

describe( 'api', () => {

	let bot;

	before( async () => {
		bot = await mwbot();
	} );

	it( 'can create a user', async () => {
		const username = getTestString( 'User-' );
		const password = getTestString();
		const result = await createAccount( bot, username, password );
		expect( result.createaccount.status ).toEqual( 'PASS' );
	} );

	it( 'can read a page', async () => {
		const pageName = 'Main Page';
		const response = await bot.read( pageName );
		// Page id below 0 means the page do not exixts
		expect( Object.values( response.query.pages )[ 0 ].pageid ).toBeGreaterThan( 0 );
	} );
} );
