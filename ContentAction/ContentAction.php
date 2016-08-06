<?php
/**
 * An extension that demonstrates how to use the SkinTemplateContentActions
 * hook to add a new content action
 *
 * @author Ævar Arnfjörð Bjarmason <avarab@gmail.com>
 * @copyright Copyright © 2005, Ævar Arnfjörð Bjarmason
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 */

class ContentAction {

	public static function onSkinTemplateNavigation( $skin, &$content_actions ) {
		$action = $skin->getRequest->getText( 'action' );

		if ( $skin->getTitle()->getNamespace() != NS_SPECIAL ) {
			$content_actions['actions']['myact'] = array(
				'class' => $action === 'myact' ? 'selected' : false,
				'text' => wfMessage( 'contentaction-myact' )->text(),
				'href' => $skin->getTitle()->getLocalUrl( 'action=myact' )
			);
		}

		return true;
	}

	public static function onUnknownAction( $action, $article ) {
		$title = $article->getTitle();

		if ( $action === 'myact' ) {
			$article->getContext()->getOutput()->addWikiText(
				'The page name is ' . $title->getText() . ' and you are ' . $article->getUserText()
			);
			return false;
		}

		return true;
	}
}
