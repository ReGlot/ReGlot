<?php
/*
 * Check this document out http://www.innerweaver.com/articles/php-java-properties for java to php properties accessibility
*/

class GP_Format_Java {
	
	var $name = 'Java Properties (.props|.properties)';

	/**
	 *
	 * TODO all of this class needs to be written still
	 * 
	 */
	var $allowedCharsInKey = '[a-zA-Z0-9_:\\.]';

	var $extension = 'php';
	
	var $exported = '';
	
	function line($string, $prepend_tabs = 0) {
		$this->exported .= str_repeat("\t", $prepend_tabs) . "$string\n";
	}
		
	function print_exported_file($project, $locale, $translation_set, $entries) {
		$this->exported = '';
		$this->line('<?php');
		$this->line(" * {$project->name} ({$locale->name})");
		$this->line(' *');
		$this->line(" * @package {$project->slug}.{$translation_set->slug}");
		$this->line(" * @subpackage Languages.{$translation_set->name}");
		$this->line(' */');
		$this->line();
		$this->line('$localized = array(');
		foreach ( $entries as $entry ) {
			if ( !preg_match( "/^{$this->allowedCharsInKey}+$/", $entry->context ) ) {
				error_log('Elgg PHP Export: Bad Entry: '. $entry->context);
				continue;
			}
			if ( !empty($entry->extracted_comments) ) {
				$this->line('/// ' . $this->escape($entry->extracted_comments), 1);
			}
			$this->line('\'' . $entry->context . '\' => ' . $this->escape($entry->translations[0]) . ',', 1);
		}
		$this->line(');');
		$this->line();
		$this->line("add_translation('{$locale->slug}', \$localized);");
		return $this->exported;
	}

	function read_translations_from_file($file_name, $project = null) {
		if ( is_null( $project ) ) return false;
		$translations = $this->read_originals_from_file($file_name);
		if ( !$translations ) return false;
		$originals = GP::$original->by_project_id($project->id);
		$new_translations = new Translations();
		foreach ( $translations->entries as $key => $entry ) {
			// we have been using read_originals_from_file to parse the file
			// so we need to swap singular and translation			
			$entry->translations = array($entry->singular);
			$entry->singular = null;
			foreach ( $originals as $original ) {
				if ( $original->context == $entry->context ) {
					$entry->singular = $original->singular;
					break;
				}
			}
			if ( !$entry->singular ) {
				error_log(sprintf(__("Missing context %s in project #%d"), $entry->context, $project->id));
				continue;
			}
			
			$new_translations->add_entry($entry);
		}
		return $new_translations;
	}

	function read_originals_from_file($file_name) {
		$data = file($file_name);
		$entries = new Translations();
		$in_language_array = false;
		$comment = '';
		foreach ( $data as $line ) {
			if ( !$in_language_array ) {
				if ( preg_match('/^\\$[a-z0-9_]+\\s*=\\s*array\\($/', trim($line)) ) {
					$in_language_array = true;
				}
				continue;
			} else {
				if ( preg_match( '/^\/\/\/\\s*(.*)$/', trim($line), $matches ) ) {
					$comment = $matches[1];
				} else if ( preg_match("/^(['\"])({$this->allowedCharsInKey}+)\\1\\s*=>\\s*(['\"])(.+)\\3\\s*,$/", trim($line), $matches) ) {
					$entry = new Translation_Entry();
					$entry->context = $matches[2];
					$entry->singular = $this->unescape($matches[4]);
					if ( !empty($comment) ) {
						$entry->extracted_comments = $comment;
					}
					$entry->translations = array();
					$entries->add_entry($entry);
					$comment = '';
				} else if ( substr(trim($line), 0, 2) == ');' ) {
					break;
				} else {
					continue;
				}
			}
		}
		return $entries;
	}

	
	function unescape($string) {
		return stripcslashes($string);		
	}

	function escape($string) {
		$newstring = addcslashes($string, "\0..\37'\"\177..\377");
		if ( $newstring != $string ) {
			return "\"$string\"";
		} else {
			return "'$string'";
		}
	}
}

GP::$formats['java'] = new GP_Format_Java();