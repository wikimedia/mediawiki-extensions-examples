<?php

namespace MediaWiki\Extension\Example;

use Wikimedia\ParamValidator\ParamValidator;
use MediaWiki\Rest\SimpleHandler;

/**
 * Example class to echo a path parameter
 */
class RestApiExample extends SimpleHandler {

	private const VALID_ACTIONS = [ 'reverse', 'shuffle', 'md5' ];

	public function run( $valueToEcho, $action ) {
		switch ( $action ) {
			case 'reverse':
				return [ 'echo' => strrev( $valueToEcho ) ];

			case 'shuffle':
				return [ 'echo' => str_shuffle( $valueToEcho ) ];

			case 'md5':
				return [ 'echo' => md5( $valueToEcho ) ];

			default:
				return [ ' echo' => $valueToEcho ];
		}
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
			'text_action' => [
				self::PARAM_SOURCE => 'path',
				ParamValidator::PARAM_TYPE => self::VALID_ACTIONS,
				ParamValidator::PARAM_REQUIRED => false,
			],
		];
	}
}
