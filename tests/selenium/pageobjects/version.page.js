// Example code for Selenium/How-to/Create the first test in a repository
// https://www.mediawiki.org/wiki/Selenium/How-to/Create_the_first_test_in_a_repository

'use strict';

const Page = require( 'wdio-mediawiki/Page' );

// this is just a sample on how to create a page
class VersionPage extends Page {
	// this is just a sample on how to find an element
	get extension() {
		return $( '#mw-version-ext-other-examples' );
	}

	// this is just a sample on how to open a page
	async open() {
		return super.openTitle( 'Special:Version' );
	}
}

module.exports = new VersionPage();
