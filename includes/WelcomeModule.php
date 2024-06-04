<?php

namespace MediaWiki\Extension\Example;

use MediaWiki\ResourceLoader\Context;
use MediaWiki\ResourceLoader\FileModule;

class WelcomeModule extends FileModule {
	/** @inheritDoc */
	public function getScript( Context $context ) {
		$conf = $this->getConfig();
		return \Xml::encodeJsCall( 'mw.config.set', [ [
			'wgExampleWelcomeColorDays' => $conf->get( 'ExampleWelcomeColorDays' ),
			'wgExampleWelcomeColorDefault' => $conf->get( 'ExampleWelcomeColorDefault' ),
			] ] )
			. parent::getScript( $context );
	}

	/** @return bool */
	public function enableModuleContentVersion() {
		return true;
	}

	/** @return bool */
	public function supportsURLLoading() {
		return false;
	}
}
