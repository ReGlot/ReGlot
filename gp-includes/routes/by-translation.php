<?php
class GP_Route_ByTranslation extends GP_Route_Main {
	
	function index($kind) {
		$title = __('Translations');
		switch ( $kind ) {
			case 'locales':
				$bundles = GP::$translation_bundle->all_by_locale();
				break;
			case 'slugs':
				$bundles = GP::$translation_bundle->all_by_slug();
				break;
			case 'both':
				$bundles = GP::$translation_bundle->all_by_both();
				break;
		}
		$this->tmpl('by-translation', get_defined_vars());
	}

}