<?php
class GP_Route_ByTranslation extends GP_Route_Main {
	
	function index($kind) {
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

	function get_by_translation($kind, $value1, $value2 = null) {
		switch ( $kind ) {
			case 'locale':
				$translation_sets = GP::$project->sets_by_locale($value1);
				$locale_slug = $value1;
				$slug = null;
				break;
			case 'slug':
				$translation_sets = GP::$project->sets_by_slug($value1);
				$slug = $value1;
				$locale_slug = null;
				break;
			case 'both':
				$translation_sets = GP::$project->sets_by_both($value1, $value2);
				$locale_slug = $value1;
				$slug = $value2;
				break;
		}
		foreach( $translation_sets as $set ) {
			$set->display_name = $set->name;
			$set->current_count = $set->current_count($locale_slug, $slug);
			$set->untranslated_count = $set->untranslated_count($locale_slug, $slug);
			$set->waiting_count = $set->waiting_count($locale_slug, $slug);
			$set->percent_translated = $set->percent_translated($locale_slug, $slug);
			$set->all_count = $set->all_count();
		}

		$this->tmpl('projects-by-translation', get_defined_vars());
	}
}