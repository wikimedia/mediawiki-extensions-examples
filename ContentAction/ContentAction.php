<?php
/**
 * An extension that demonstrates how to create a new page action.
 *
 * @author Ævar Arnfjörð Bjarmason <avarab@gmail.com>
 * @copyright Copyright © 2005, Ævar Arnfjörð Bjarmason
 * @license GPL-2.0-or-later
 */

class ContentAction extends FormlessAction {
	public function getName() {
		return 'myact';
	}

	protected function getDescription() {
		// Disable subtitle under page heading
		return '';
	}

	public function onView() {
		return null;
	}

	public function show() {
		parent::show();

		$this->getContext()->getOutput()->addWikiTextAsInterface(
			'This is a custom action for page [[' . $this->getTitle()->getText() . ']].'
		);
	}

}
