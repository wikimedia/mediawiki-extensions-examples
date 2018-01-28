<?php
/**
 *
 * Copyright © 25.05.13 by the authors listed below.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @license GPL 2+
 * @file
 *
 * @author Daniel Kinzler
 */

/**
 * Class XmlContent represents XML content.
 *
 * This is based on TextContent, and represents XML as a string.
 *
 * Using a text based content model is the simplest option, since it
 * allows the use of the standard editor and diff code, and does not require
 * serialization/unserialization.
 *
 * However, text based content is "dumb". It means that your code can't make use
 * of the XML structure, making it a bit pointless. If you want to use and manipulate
 * the XML structure, then it should be represented as a DOM inside the XmlContent.
 *
 * In that case, XmlContentHandler::serializeContent()
 * and XmlContentHandler::unserializeContent() would have to be used for
 * serializing resp. parsing the XML DOM.
 *
 * Also, a special editor might be needed to interact with the structure.
 *
 * @package DataPages
 */
class XmlContent extends \TextContent {

	public function __construct( $text, $model_id = CONTENT_MODEL_XML_DATA ) {
		parent::__construct( $text, $model_id );
	}

	public function getHtml() {
		// We could put pretty printing and syntax highlighting here.
		// And maybe throw in some JS for collapsible sections.
		// For now, let's assume the XML is already pretty.

		$html = htmlspecialchars( $this->getNativeData() );
		$html = "<pre>" . $html . "</pre>";
		return $html;
	}

	public function getParserOutput( Title $title,
		$revId = null,
		ParserOptions $options = null, $generateHtml = true
	) {
		// If you want to have more control over parsing than getHtml() gives you,
		// you can control the construction of the ParserOutput object and add
		// meta data like categories, etc. based on the content.

		return parent::getParserOutput( $title, $revId, $options, $generateHtml );
	}

	public function isEmpty() {
		// Determines whether this content can be considered empty.
		// For XML, we want to check whether there's any CDATA:

		$text = trim( strip_tags( $this->getNativeData() ) );
		return $text === '';
	}

	public function isCountable( $hasLinks = null ) {
		// Determines whether this content should be counted as a "page" for the wiki's statistics.
		// Here, we require it to be not-empty and not a redirect:
		return !$this->isEmpty() && !$this->isRedirect();
	}

	public function prepareSave( WikiPage $page, $flags, $baseRevId, User $user ) {
		// Called before saving an edit.
		// This is the preferred place for checking constraints, be they
		// on the content itself, or for global consistency.
		//
		// Alternatively, validity can be checked in isValid(), but there
		// we have no way to provide a detailed error report to the user.
		//
		// NOTE: For checking even on preview, we'd need a custom editor.
		// A nicer way to do this might be added to the ContentHandler facility in the future.

		libxml_use_internal_errors( true );
		$doc = simplexml_load_string( $this->getNativeData() );

		$errors = libxml_get_errors();

		$status = Status::newGood();

		if ( !$doc || $errors ) {
			// construct an informative error message here!

			$param1 = array_reduce( // fancy way to concatenate the messages from LibXMLError objects
				$errors,
				function ( $msg, $error ) {
					if ( $msg !== '' ) {
						$msg .= '; ';
					}
					$msg .= "line " . $error->line . ": " . $error->message;
					return $msg;
				},
				''
			);

			// you should use a more meaningful message, if possible
			$status->fatal( 'content-failed-to-parse', "XML", "", $param1 );
		}

		return $status;
	}

	public function isValid() {
		// This is a last line of defense against storing invalid data.
		// It can be used to check validity, as an alternative to doing so
		// in prepareSave().
		//
		// Checking here has the advantage that this is ALWAYS called before
		// the content is saved to the database, no matter whether the content
		// was edited, imported, restored, or what.
		//
		// The downside is that it's too late here for meaningful interaction
		// with the user, we can just abort the save operation, casing an internal
		// error.

		return parent::isValid();
	}

	public function getTextForSearchIndex() {
		// Should return text relevant to the wiki's search index, for instance by stripping tags:
		return strip_tags( $this->getNativeData() );
	}

	public function convert( $toModel, $lossy = '' ) {
		// Implement conversion to other content models.
		// Text based models can per default be converted to all other text based models.

		return parent::convert( $toModel, $lossy );
	}

	public function getSection( $sectionId ) {
		// We could implement sections as XML elements based on their id attribute.
		// If XmlContent was DOM based, that would be nice and easy.
		return parent::getSection( $sectionId );
	}

	public function replaceSection( $section, Content $with, $sectionTitle = '' ) {
		// If we want to support sections, we also have to provide a way to substitute them,
		// for section based editing.
		return parent::replaceSection( $section, $with, $sectionTitle );
	}
}
