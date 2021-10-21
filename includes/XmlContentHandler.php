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
use IContextSource;
use MediaWiki\Content\Renderer\ContentParseParams;
use ParserOutput;
use TextContentHandler;

/**
 * Class XmlContentHandler represents the set of operations for XMLContent that can be
 * performed without the actual content. Most importantly, it acts as a factory
 * and serialization/unserialization service for XmlContent objects.
 *
 * This extends TextContentHandler because XmlContent extends TextContent.
 * Content and ContentHandler implementations are generally paired like this.
 * See the documentation of XmlContent for more information.
 *
 * @package DataPages
 */
class XmlContentHandler extends TextContentHandler {

	/** @inheritDoc */
	public function __construct(
		$modelId = XmlContent::MODEL,
		$formats = [ CONTENT_FORMAT_XML ]  // XML is supported as a serialization format by default
	) {
		parent::__construct( $modelId, $formats );
	}

	/** @inheritDoc */
	public function serializeContent( Content $content, $format = null ) {
		// No special logic needed; XmlContent just wraps the raw text.
		// If XmlContent were DOM-based, we'd serialize the XML DOM here.
		return parent::serializeContent( $content, $format );
	}

	/** @inheritDoc */
	public function unserializeContent( $text, $format = null ) {
		// No special logic needed; XmlContent just wraps the raw text.
		// If XmlContent were DOM-based, we'd parse the XML here.
		return new XmlContent( $text );
	}

	/** @inheritDoc */
	public function makeEmptyContent() {
		return new XmlContent( '' );
	}

	/** @inheritDoc */
	public function getActionOverrides() {
		// Add an override for the edit action to specify a custom editor
		// for editing XML. The standard edit page will work as a default for
		// any text-based content.
		return parent::getActionOverrides();
	}

	/** @inheritDoc */
	public function createDifferenceEngine( IContextSource $context,
		$old = 0, $new = 0, $rcid = 0,
		$refreshCache = false, $unhide = false
	) {
		// We could provide a custom difference engine for creating and
		// rendering diffs between XML structures.
		// The default implementation is line-based, which isn't too great for XML.
		return parent::createDifferenceEngine( $context, $old, $new, $rcid, $refreshCache, $unhide );
	}

	/** @inheritDoc */
	public function supportsSections() {
		// return true if XmlContent implements section-handling
		return parent::supportsSections();
	}

	/** @inheritDoc */
	public function supportsRedirects() {
		// return true if XmlContent supports representing redirects
		return parent::supportsRedirects();
	}

	/** @inheritDoc */
	public function merge3( Content $oldContent, Content $myContent, Content $yourContent ) {
		// You could implement smart DOM-based diff/merge here.
		// The default implementation is line-based, which isn't too great for XML.
		return parent::merge3( $oldContent, $myContent, $yourContent );
	}

	/** @inheritDoc */
	protected function fillParserOutput(
		Content $content,
		ContentParseParams $cpoParams,
		ParserOutput &$output
	) {
		'@phan-var XmlContent $content';
		parent::fillParserOutput( $content, $cpoParams, $output );

		if ( $cpoParams->getGenerateHtml() ) {
			// Returns HTML for this content, as displayed on page.
			$html = htmlspecialchars( $content->getText() );
			$html = "<pre>" . $html . "</pre>";
		} else {
			$html = '';
		}

		$output->clearWrapperDivClass();
		$output->setText( $html );
	}
}
