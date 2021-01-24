<?php
/**
 * HelloWorld Special page.
 *
 * @file
 */

namespace MediaWiki\Extension\Example;

class SpecialHelloWorld extends \SpecialPage {

	/**
	 * Initialize the special page.
	 */
	public function __construct() {
		// A special page should at least have a name.
		// We do this by calling the parent class (the SpecialPage class)
		// constructor method with the name as first and only parameter.
		parent::__construct( 'HelloWorld' );
	}

	/**
	 * Shows the page to the user.
	 * @param string $sub The subpage string argument (if any).
	 *  [[Special:HelloWorld/subpage]].
	 */
	public function execute( $sub ) {
		$out = $this->getOutput();

		$out->setPageTitle( $this->msg( 'example-helloworld' ) );

		// Parses message from .i18n.php as wikitext and adds it to the
		// page output.
		$out->addWikiMsg( 'example-helloworld-intro' );
	}

	/** @inheritDoc */
	protected function getGroupName() {
		return 'other';
	}
}
