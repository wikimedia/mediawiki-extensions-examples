<?php

namespace MediaWiki\Extension\Example;

use ApiBase;

class ApiQueryExample extends \ApiQueryBase {

	/**
	 * Constructor is optional. Only needed if we give
	 * this module properties a prefix (in this case we're using
	 * "ex" as the prefix for the module's properties.
	 * Query modules have the convention to use a property prefix.
	 * Base modules generally don't use a prefix, and as such don't
	 * need the constructor in most cases.
	 * @param \ApiQuery $query
	 * @param string $moduleName
	 */
	public function __construct( $query, $moduleName ) {
		parent::__construct( $query, $moduleName, 'ex' );
	}

	/**
	 * In this example we're returning one ore more properties
	 * of wgExampleFooStuff. In a more realistic example, this
	 * method would probably
	 */
	public function execute() {
		global $wgExampleFooStuff;
		$params = $this->extractRequestParams();

		$stuff = [];

		// This is a filtered request, only show this key if it exists,
		// (or none, if it doesn't exist)
		if ( isset( $params['key'] ) ) {
			$key = $params['key'];
			if ( isset( $wgExampleFooStuff[$key] ) ) {
				$stuff[$key] = $wgExampleFooStuff[$key];
			}

		// This is an unfiltered request, replace the array with the total
		// set of properties instead.
		} else {
			$stuff = $wgExampleFooStuff;
		}

		$r = [ 'stuff' => $stuff ];
		$this->getResult()->addValue( null, $this->getModuleName(), $r );
	}

	/** @inheritDoc */
	public function getAllowedParams() {
		return [
			'key' => [
				ApiBase::PARAM_TYPE => 'string',
			],
		];
	}

	/** @inheritDoc */
	protected function getExamplesMessages() {
		return [
			'action=query&list=example'
				=> 'apihelp-query+example-example-1',
			'action=query&list=example&key=do'
				=> 'apihelp-query+example-example-2',
		];
	}
}
