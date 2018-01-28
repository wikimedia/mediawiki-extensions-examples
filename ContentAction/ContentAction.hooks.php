<?php
/**
 * Hooks for ContentAction extension
 *
 * @file
 * @ingroup Extensions
 */
class ContentActionHooks {
	/**
	 * https://www.mediawiki.org/wiki/Manual:Hooks/SkinTemplateNavigation
	 *
	 * @param SkinTemplate $skin
	 * @param array &$content_actions
	 */
	public static function onSkinTemplateNavigation( SkinTemplate $skin, array &$content_actions ) {
		$action = $skin->getRequest()->getText( 'action' );

		if ( $skin->getTitle()->getNamespace() != NS_SPECIAL ) {
			$content_actions['actions']['myact'] = [
				'class' => $action === 'myact' ? 'selected' : false,
				'text' => wfMessage( 'contentaction-myact' )->text(),
				'href' => $skin->getTitle()->getLocalUrl( 'action=myact' )
			];
		}
	}
}
