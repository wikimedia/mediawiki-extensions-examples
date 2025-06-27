// Example code for Selenium/How-to/Create the first test in a repository
// https://www.mediawiki.org/wiki/Selenium/How-to/Create_the_first_test_in_a_repository

// this is just a sample on how to use a page
import VersionPage from '../pageobjects/version.page.js';

describe( 'Examples', () => {

	// this is just a sample test
	it( 'is configured correctly', async () => {
		await VersionPage.open();

		// this is just a sample assertion, checking if an element exists
		await expect( await VersionPage.extension ).toExist();

	} );

} );
