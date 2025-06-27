// Example code for Selenium/How-to/Create the first test in a repository
// https://www.mediawiki.org/wiki/Selenium/How-to/Create_the_first_test_in_a_repository

import { config as wdioDefaults } from 'wdio-mediawiki/wdio-defaults.conf.js';

export const config = { ...wdioDefaults
	// Override, or add to, the setting from wdio-mediawiki.
	// Learn more at https://webdriver.io/docs/configurationfile/
	//
	// Example:
	// logLevel: 'info',
};
