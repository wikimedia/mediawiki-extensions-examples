<?php
/**
 * Hooks for Example extension.
 *
 * @file
 */

namespace MediaWiki\Extension\Example;

use DatabaseUpdater;

class MoreHooks implements
	\MediaWiki\Parser\Hook\GetMagicVariableIDsHook,
	\MediaWiki\Installer\Hook\LoadExtensionSchemaUpdatesHook
{

	/**
	 * @see https://www.mediawiki.org/wiki/Manual:Hooks/GetMagicVariableIDs
	 * @param string[] &$variableIDs
	 */
	public function onGetMagicVariableIDs( &$variableIDs ) {
		// Add the following to a wiki page to see how it works:
		// {{MYWORD}}
		$variableIDs[] = 'myword';
	}

	/**
	 * Register our database schema.
	 *
	 * @see https://www.mediawiki.org/wiki/Manual:Hooks/LoadExtensionSchemaUpdates
	 * @param DatabaseUpdater $updater
	 */
	public function onLoadExtensionSchemaUpdates( $updater ) {
		$dbType = $updater->getDB()->getType();
		$dir = __DIR__ . '/../sql';

		if ( $dbType !== 'mysql' ) {
			$dir .= "/$dbType";
		}

		$updater->addExtensionTable( 'example_note', "$dir/tables-generated.sql" );
	}
}
