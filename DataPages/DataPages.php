<?php
/**
 * Data pages extension, an example for how to use the ContentHandler
 * facility to handle XML content in MediaWiki.
 *
 * See DataPages.example.php for information on what to put into
 * your LocalSettings file.
 *
 * Note that this example represents XML as a string internally.
 * More advanced implementation may want to use a DOM based approach.
 *
 * Please look at XmlContentHandler.php and XmlContent.php to see what
 * can and should be done to support custom data types.
 *
 * @file
 * @ingroup Extensions
 * @author Daniel Kinzler, brightbyte.de
 *
 * @copyright Wikimedia Deutschland e.V., 2013
 * @license GNU General Public Licence 2.0 or later
 */

if ( !defined( 'MEDIAWIKI' ) ) {
	echo "This file is an extension to the MediaWiki software and cannot be used on its own.\n";
	die( 1 );
}

if ( version_compare( $wgVersion, '1.21', '<' ) ) {
	die( "This extension requires MediaWiki 1.21+\n" );
}

$wgExtensionCredits['other'][] = array(
	'path' => __FILE__,
	'name' => 'DataPages',
	'author' => 'Daniel Kinzler',
);

$wgAutoloadClasses['XmlContentHandler'] = __DIR__ . '/XmlContentHandler.php';
$wgAutoloadClasses['XmlContent'] = __DIR__ . '/XmlContent.php';

// Define a constant for the identifier of our custom content model...
define( 'CONTENT_MODEL_XML_DATA', 'XML_DATA' );

// ...and register a handler for that content model.
$wgContentHandlers[CONTENT_MODEL_XML_DATA] = 'XmlContentHandler';

// The content model is typically associated with a namespace later,
// see DataPages.example.php
