<?php
/**
 * A Special page example that can be included on a wikipage like
 * {{Special:Includable}}, as well as being accessed on [[Special:Includable]].
 *
 * @file
 * @copyright 2005 Ævar Arnfjörð Bjarmason <avarab@gmail.com>
 * @copyright Niklas Laxström
 * @license GPL-2.0-or-later
 */

namespace MediaWiki\Extension\Example;

class SpecialIncludable extends \IncludableSpecialPage {

	public function __construct() {
		parent::__construct( 'Includable' );
	}

	/**
	 * Show the page
	 */
	public function execute( $par = null ) {
		if ( $this->including() ) {
			$out = "I'm being included";
		} else {
			$out = "I'm being viewed as a Special Page";
			$this->setHeaders();
		}

		$this->getOutput()->addWikiTextAsInterface( $out );
	}
}
