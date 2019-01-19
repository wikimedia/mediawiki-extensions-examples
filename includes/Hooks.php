<?php
/**
 * Hooks for Example extension.
 *
 * @file
 */

namespace MediaWiki\Extension\Example;

use FormatJson;
use OutputPage;
use PPFrame;
use Skin;
use Parser;
use DatabaseUpdater;
use SkinTemplate;

class Hooks {
	/**
	 * Customisations to OutputPage right before page display.
	 *
	 * @see https://www.mediawiki.org/wiki/Manual:Hooks/BeforePageDisplay
	 */
	public static function onBeforePageDisplay( OutputPage $out, Skin $skin ) {
		global $wgExampleEnableWelcome;

		if ( $wgExampleEnableWelcome ) {
			// Load our module on all pages
			$out->addModules( 'ext.Example.welcome' );
		}
	}

	/**
	 * Register parser hooks.
	 *
	 * @see https://www.mediawiki.org/wiki/Manual:Hooks/ParserFirstCallInit
	 * @see https://www.mediawiki.org/wiki/Manual:Parser_functions
	 * @param Parser $parser
	 */
	public static function onParserFirstCallInit( Parser $parser ) {
		// Add the following to a wiki page to see how it works:
		// <dump>test</dump>
		// <dump foo="bar" baz="quux">test content</dump>
		$parser->setHook( 'dump', [ self::class, 'parserTagDump' ] );

		// Add the following to a wiki page to see how it works:
		// {{#echo: hello }}
		$parser->setFunctionHook( 'echo', [ self::class, 'parserFunctionEcho' ] );

		// Add the following to a wiki page to see how it works:
		// {{#showme: hello | hi | there }}
		$parser->setFunctionHook( 'showme', [ self::class, 'parserFunctionShowme' ] );
	}

	/**
	 * @see https://www.mediawiki.org/wiki/Manual:Hooks/MagicWordwgVariableIDs
	 * @param array &$magicWordsIds
	 */
	public static function onMagicWordwgVariableIDs( array &$magicWordsIds ) {
		// Add the following to a wiki page to see how it works:
		// {{MYWORD}}
		$magicWordsIds[] = 'myword';
	}

	/**
	 * @see https://www.mediawiki.org/wiki/Manual:Hooks/ParserGetVariableValueSwitch
	 */
	public static function onParserGetVariableValueSwitch( $parser, $cache, &$magicWordId, &$ret ) {
		if ( $magicWordId === 'myword' ) {
			// Return value and cache should match. Cache is used to save
			// additional call when it is used multiple times on a page.
			$ret = $cache['myword'] = self::parserGetWordMyword();
		}
	}

	/**
	 * Register our database schema.
	 *
	 * @see https://www.mediawiki.org/wiki/Manual:Hooks/LoadExtensionSchemaUpdates
	 * @param DatabaseUpdater $updater
	 */
	public static function onLoadExtensionSchemaUpdates( DatabaseUpdater $updater ) {
		$updater->addExtensionTable( 'example_note', dirname( __DIR__ ) . '/sql/add-example_note.sql' );
	}

	/**
	 * @see https://www.mediawiki.org/wiki/Manual:Hooks/ResourceLoaderTestModules
	 */
	public static function onResourceLoaderTestModules( array &$modules ) {
		$modules['qunit']['ext.Example.tests'] = [
			'dependencies' => [
				'ext.Example.welcome',
			],
			'localBasePath' => dirname( __DIR__ ) . '/tests/qunit',
			'remoteExtPath' => 'examples/Example/tests/qunit',
			'scripts' => [
				'ext.Example.welcome.test.js',
			],
		];
	}

	/**
	 * @see https://www.mediawiki.org/wiki/Manual:Hooks/SkinTemplateNavigation
	 * @param SkinTemplate $skin
	 * @param array &$cactions
	 */
	public static function onSkinTemplateNavigation( SkinTemplate $skin, array &$cactions ) {
		$action = $skin->getRequest()->getText( 'action' );

		if ( $skin->getTitle()->getNamespace() !== NS_SPECIAL ) {
			$cactions['actions']['myact'] = [
				'class' => $action === 'myact' ? 'selected' : false,
				'text' => wfMessage( 'contentaction-myact' )->text(),
				'href' => $skin->getTitle()->getLocalURL( 'action=myact' ),
			];
		}
	}

	/**
	 * Parser magic word handler for {{MYWORD}}.
	 *
	 * @return string Wikitext to be rendered in the page.
	 */
	public static function parserGetWordMyword() {
		global $wgExampleMyWord;

		return wfEscapeWikiText( $wgExampleMyWord );
	}

	/**
	 * Parser hook handler for <dump>
	 *
	 * @param string $data The content of the tag.
	 * @param array $attribs The attributes of the tag.
	 * @param Parser $parser Parser instance available to render
	 *  wikitext into html, or parser methods.
	 * @param PPFrame $frame Can be used to see what template
	 *  arguments ({{{1}}}) this hook was used with.
	 * @return string HTML to insert in the page.
	 */
	public static function parserTagDump( $data, $attribs, $parser, $frame ) {
		$dump = [
			'content' => $data,
			'atributes' => (object)$attribs,
		];

		// Very important to escape user data with htmlspecialchars() to prevent
		// an XSS security vulnerability.
		$html = '<pre>Dump Tag: '
			. htmlspecialchars( FormatJson::encode( $dump, /*prettyPrint=*/true ) )
			. '</pre>';

		return $html;
	}

	/**
	 * Parser function handler for {{#echo: .. }}
	 *
	 * @param Parser $parser
	 * @param string $value
	 *
	 * @return string HTML to insert in the page.
	 */
	public static function parserFunctionEcho( Parser $parser, $value ) {
		return '<strong>Echo says: ' . htmlspecialchars( $value ) . '</strong>';
	}

	/**
	 * Parser function handler for {{#showme: .. | .. }}
	 *
	 * @param Parser $parser
	 * @param string $arg
	 *
	 * @return string HTML to insert in the page.
	 */
	public static function parserFunctionShowme( Parser $parser, $value /* arg2, arg3, */ ) {
		$args = array_slice( func_get_args(), 2 );
		$showme = [
			'value' => $value,
			'arguments' => $args,
		];

		return '<pre>Showme Function: '
			. htmlspecialchars( FormatJson::encode( $showme, /*prettyPrint=*/true ) )
			. '</pre>';
	}
}
