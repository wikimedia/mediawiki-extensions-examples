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
 * @license GPL-2.0-or-later
 * @file
 *
 * @author Daniel Kinzler
 */
namespace MediaWiki\Extension\Example;

use Content;
use ParserOptions;
use ParserOutput;
use Status;
use TextContent;
use Title;
use User;
use WikiPage;

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
class XmlContent extends TextContent {
	public const MODEL = 'xmldata';

	/** @inheritDoc */
	public function __construct( $text, $model_id = self::MODEL ) {
		parent::__construct( $text, $model_id );
	}

	/**
	 * Returns HTML for this content, as displayed on page.
	 *
	 * We could put pretty printing and syntax highlighting here.
	 * And maybe throw in some JS for collapsible sections.
	 * For now, let's assume the XML is already pretty.
	 *
	 * @return string
	 */
	public function getHtml() {
		$html = htmlspecialchars( $this->getText() );
		$html = "<pre>" . $html . "</pre>";
		return $html;
	}

	/**
	 * If you want to have more control over parsing than getHtml() gives you,
	 * you can control the construction of the ParserOutput object and add
	 * meta data like categories, etc. based on the content.
	 *
	 * @param Title $title
	 * @param int|null $revId
	 * @param ParserOptions|null $options
	 * @param bool $generateHtml
	 *
	 * @return ParserOutput
	 */
	public function getParserOutput( Title $title,
		$revId = null,
		ParserOptions $options = null, $generateHtml = true
	) {
		return parent::getParserOutput( $title, $revId, $options, $generateHtml );
	}

	/**
	 * Determines whether this content can be considered empty.
	 * For XML, we want to check whether there's any CDATA:
	 *
	 * @return bool
	 */
	public function isEmpty() {
		$text = trim( strip_tags( $this->getText() ) );
		return $text === '';
	}

	/**
	 * Determines whether this content should be counted as a "page" for the wiki's statistics.
	 * Here, we require it to be not-empty and not a redirect.
	 *
	 * @param bool|null $hasLinks
	 *
	 * @return bool
	 */
	public function isCountable( $hasLinks = null ) {
		return !$this->isEmpty() && !$this->isRedirect();
	}

	/**
	 * Called before saving an edit.
	 * This is the preferred place for checking constraints, be they
	 * on the content itself, or for global consistency.
	 *
	 * Alternatively, validity can be checked in isValid(), but there
	 * we have no way to provide a detailed error report to the user.
	 *
	 * NOTE: For checking even on preview, we'd need a custom editor.
	 * A nicer way to do this might be added to the ContentHandler facility in the future.
	 *
	 * @param WikiPage $page
	 * @param int $flags
	 * @param int $parentRevId
	 * @param User $user
	 *
	 * @return Status
	 */
	public function prepareSave( WikiPage $page, $flags, $parentRevId, User $user ) {
		libxml_use_internal_errors( true );
		$doc = simplexml_load_string( $this->getText() );

		$errors = libxml_get_errors();

		$status = Status::newGood();

		if ( !$doc || $errors ) {
			// construct an informative error message here!

			$param1 = array_reduce( // fancy way to concatenate the messages from LibXMLError objects
				$errors,
				static function ( $msg, $error ) {
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

	/**
	 * This is a last line of defense against storing invalid data.
	 * It can be used to check validity, as an alternative to doing so
	 * in prepareSave().
	 *
	 * Checking here has the advantage that this is ALWAYS called before
	 * the content is saved to the database, no matter whether the content
	 * was edited, imported, restored, or what.
	 *
	 * The downside is that it's too late here for meaningful interaction
	 * with the user, we can just abort the save operation, causing an internal
	 * error.
	 *
	 * @return bool
	 */
	public function isValid() {
		return parent::isValid();
	}

	/**
	 * Should return text relevant to the wiki's search index, for instance by stripping tags.
	 *
	 * @return string
	 */
	public function getTextForSearchIndex() {
		return strip_tags( $this->getText() );
	}

	/**
	 * Implement conversion to other content models.
	 * Text based models can per default be converted to all other text based models.
	 *
	 * @param string $toModel
	 * @param string $lossy
	 *
	 * @return string
	 */
	public function convert( $toModel, $lossy = '' ) {
		return parent::convert( $toModel, $lossy );
	}

	/**
	 * We could implement sections as XML elements based on their id attribute.
	 * If XmlContent was DOM based, that would be nice and easy.
	 *
	 * @param string|int $sectionId
	 *
	 * @return Content|bool|null
	 */
	public function getSection( $sectionId ) {
		return parent::getSection( $sectionId );
	}

	/**
	 * If we want to support sections, we also have to provide a way to substitute them,
	 * for section based editing.
	 *
	 * @param string|int|null|bool $sectionId
	 * @param Content $with
	 * @param string $sectionTitle
	 *
	 * @return Content|null
	 */
	public function replaceSection( $sectionId, Content $with, $sectionTitle = '' ) {
		return parent::replaceSection( $sectionId, $with, $sectionTitle );
	}
}
