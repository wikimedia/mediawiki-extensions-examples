<?php

namespace MediaWiki\Extension\Example;

use Wikimedia\ParamValidator\ParamValidator;
use MediaWiki\Rest\SimpleHandler;

/**
 * Example class to echo a path parameter
 */
class RestApiExample extends SimpleHandler {
	public function run( $valueToEcho ) {
		return [ 'echo' => $valueToEcho ];
	}

	public function needsWriteAccess() {
		return false;
	}

	public function getParamSettings() {
		return [
			'value_to_echo' => [
				self::PARAM_SOURCE => 'path',
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_REQUIRED => true,
			],
		];
	}
}
