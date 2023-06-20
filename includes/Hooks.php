<?php
// phpcs:disable MediaWiki.NamingConventions.LowerCamelFunctionsName.FunctionName

/**
 * Hooks for Example extension.
 *
 * @file
 */

namespace MediaWiki\Extension\Example;

use FormatJson;
use MediaWiki\Permissions\PermissionManager;
use OutputPage;
use Parser;
use PPFrame;
use Skin;
use SkinTemplate;

class Hooks implements
	\MediaWiki\Hook\BeforePageDisplayHook,
	\MediaWiki\Hook\ParserFirstCallInitHook,
	\MediaWiki\Hook\ParserGetVariableValueSwitchHook,
	\MediaWiki\Hook\SkinTemplateNavigation__UniversalHook
{

	/** @var PermissionManager */
	private $permissionManager;

	/**
	 * @param PermissionManager $permissionManager example injected service
	 */
	public function __construct( PermissionManager $permissionManager ) {
		$this->permissionManager = $permissionManager;
	}

	/**
	 * Customisations to OutputPage right before page display.
	 *
	 * @see https://www.mediawiki.org/wiki/Manual:Hooks/BeforePageDisplay
	 * @param OutputPage $out
	 * @param Skin $skin
	 */
	public function onBeforePageDisplay( $out, $skin ): void {
		if ( $this->permissionManager->userCan( 'read', $out->getUser(), $out->getTitle() ) ) {
			global $wgExampleEnableWelcome;
			if ( $wgExampleEnableWelcome ) {
				// Load our module on all pages
				$out->addModules( 'ext.Example.welcome' );
			}
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
	 * @see https://www.mediawiki.org/wiki/Manual:Hooks/ParserGetVariableValueSwitch
	 * @param Parser $parser
	 * @param array &$cache
	 * @param string $magicWordId
	 * @param string &$ret
	 * @param PPFrame $frame
	 *
	 */
	public function onParserGetVariableValueSwitch( $parser, &$cache, $magicWordId, &$ret, $frame ) {
		if ( $magicWordId === 'myword' ) {
			// Return value and cache should match. Cache is used to save
			// additional call when it is used multiple times on a page.
			$ret = $cache['myword'] = self::parserGetWordMyword();
		}
	}

	/**
	 * Register parser hooks.
	 *
	 * @see https://www.mediawiki.org/wiki/Manual:Hooks/ParserFirstCallInit
	 * @see https://www.mediawiki.org/wiki/Manual:Parser_functions
	 * @param Parser $parser
	 * @throws \MWException
	 */
	public function onParserFirstCallInit( $parser ) {
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
	 * @see https://www.mediawiki.org/wiki/Manual:Hooks/SkinTemplateNavigation
	 * @param SkinTemplate $skin
	 * @param array &$content_navigation
	 */
	public function onSkinTemplateNavigation__Universal( $skin, &$content_navigation ): void {
		$action = $skin->getRequest()->getText( 'action' );

		if ( $skin->getTitle()->getNamespace() !== NS_SPECIAL ) {
			$content_navigation['actions']['myact'] = [
				'class' => $action === 'myact' ? 'selected' : false,
				'text' => $skin->msg( 'contentaction-myact' )->text(),
				'href' => $skin->getTitle()->getLocalURL( 'action=myact' ),
			];
		}
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
	 * @param string $value
	 * @param string ...$args
	 * @return string HTML to insert in the page.
	 */
	public static function parserFunctionShowme( Parser $parser, string $value, ...$args ) {
		$showme = [
			'value' => $value,
			'arguments' => $args,
		];

		return '<pre>Showme Function: '
			. htmlspecialchars( FormatJson::encode( $showme, /*prettyPrint=*/true ) )
			. '</pre>';
	}
}
