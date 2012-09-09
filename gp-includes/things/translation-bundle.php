<?php
class GP_Translation_Bundle extends GP_Thing {

	var $table_basename = 'translation_sets';
	var $field_names = array( 'locale', 'slug', 'name', 'both');
	var $non_updatable_attributes = array( 'both', 'slug', 'locale', 'name' );
	
	function all_by_slug() {
		return $this->many("SELECT DISTINCT slug, name, NULL AS locale, NULL as `both` FROM $this->table");
	}

	function all_by_locale() {
		return $this->many("SELECT DISTINCT locale, NULL AS slug, NULL AS name, NULL as `both` FROM $this->table");
	}

	function all_by_both() {
		return $this->many("SELECT DISTINCT CONCAT(slug, locale) AS `both`, locale, slug, name FROM $this->table");
	}
}
GP::$translation_bundle = new GP_Translation_Bundle();
