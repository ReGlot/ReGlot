<?php

/**
 * A class that defines the fields and methods of a GlotPress import/export format
 */

abstract class GP_Format {
	protected $name;
	protected $extension;

	function get_name() {
		return $this->name;
	}

	function get_extension() {
		return $this->extension;
	}

	abstract function print_exported_file($project, $locale, $translation_set, $entries);
	
	abstract function read_translations_from_file($file_name, $project = null);
	
	abstract function read_originals_from_file($file_name);

}
