<?php
/**
 * HelloWorld Special page.
 *
 * @file
 */

namespace MediaWiki\Extension\Example;

class SpecialHelloWorld extends \SpecialPage {

	public function __construct() {
		// Each special page must have a canonical name.
		// This is passed to the parent class as one and only required parameter.
		parent::__construct( 'HelloWorld' );
	}

	/**
	 * Run your code here and render content to the browser.
	 *
	 * @param string $sub Optional subpage in the title, as in [[Special:HelloWorld/subpage]].
	 */
	public function execute( $sub ) {
		$out = $this->getOutput();

		$out->setPageTitleMsg( $this->msg( 'example-helloworld' ) );

		// Gets localisation message from /i18n/ directory, parses it as wikitext,
		// and adds the HTML to the page output.
		$out->addWikiMsg( 'example-helloworld-intro' );

		// TODO (README.md): Don't forget to say goodbye!
	}

	/** @inheritDoc */
	protected function getGroupName() {
		return 'other';
	}
}
