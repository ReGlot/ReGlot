<?php

require_once(GP_PATH . GP_INC . 'elgg_language_packs/elgg_language_packs.php');

class GP_Route_Tools extends GP_Route_Main {
	private $coreProject;
	private $pluginProject;
	private $currentModProject;
	private $format;
	private $originals_added = 0;
	private $originals_updated = 0;
	private $translations_added = array();
	private $import_errors = array();

	function index() {
		gp_tmpl_load('tools', get_defined_vars());
	}

	function elgg_import() {
		// only admin can do this
		$this->admin_or_forbidden();
		// if it's a POST we need to import stuff
		if ( @$_POST['import']['gp_handle_settings'] == 'on' ) {
			// the uploaded file to import
			$file2upload = $_FILES['upload']['tmp_name'];
			if ( !$file2upload ) {
				register_error(elgg_echo('languagepacks:error:upload'));
				$this->elgglp_error_and_import_form(__('No file to import'));
				return;
			}
			// open the Zip file
			$zip = new ZipArchive;
			$res = $zip->open($file2upload);
			if ( $res === true ) {
				// extract the Zip file to a temporary folder
				$newdir = elgglp_tempdir();
				@mkdir($newdir);
				$zip->extractTo($newdir);
				$zip->close();
				// delete original file
				unlink($file2upload);
				unset($file2upload);
				unset($zip);
				// the languages to import
	//			$langstring = get_input('locales-selection');
				if ( $langstring ) {
					$filters['langs'] = explode('|', $langstring);
				} else {
					$filters['langs'] = null;
				}
				unset($langstring);
				// the plugins/projects to import
	//			$projstring = get_input('plugins-selection');
				if ( $projstring ) {
					$filters['projs'] = explode('|', $projstring);
				} else {
					$filters['projs'] = null;
				}
				unset($projstring);
				// overwrite existing translations (checkbox semantics are reversed)
				$filters['overwrite'] = @$_POST['import']['overwrite'] != 'on';
				// ignore English originals (checkbox semantics are reversed)
				$filters['ignore_en'] = @$_POST['import']['originals'] != 'on';
				// we need a meta file for each language mod
				$filters['needs_meta'] = true;
				// the project for Elgg core and bundled plugins to import into (if empty, one is created)
				$filters['coreproject'] = $_POST['import']['core_project'];
				// the project for third party plugins to import into (if empty, one is created)
				$filters['pluginproject'] = $_POST['import']['plugin_project'];
				// the versions we support
				$filters['elgg_release'] = array_keys(elgglp_core_plugins());
				// the method that will handle each language mod found
				$callback = array($this, 'elgglp_import_languagemod');
				// get the format object used to import language files
				$this->format = GP::$formats['elgg'];
				// main import switch
				switch ( elgglp_recurse_language_pack($newdir, $filters, $callback) ) {
					case ELGGLP_ERR_STRUCTURE:
                        elgglp_deltree($newdir);
                        $this->elgglp_error_and_import_form(__('Could not find an Elgg language pack in the ZIP file you specified'));
					case ELGGLP_ERR_VERSION:
                        elgglp_deltree($newdir);
                        $this->elgglp_error_and_import_form(__('The version of your language pack is not supported by this plugin'));
					case ELGGLP_OK:
						elgglp_deltree($newdir);
						$all_langs = array_keys($this->translations_added);
						$allsubprojects1 = $this->coreProject->sub_projects();
						$allsubprojects2 = $this->pluginProject->sub_projects();
						$allsubprojects = array_merge($allsubprojects1, $allsubprojects2);
						unset($allsubprojects1);
						unset($allsubprojects2);
						foreach ( $allsubprojects as $subproject ) {
							foreach ( $all_langs as $lang ) {
								if ( $lang != 'en' && !($ts = GP::$translation_set->by_project_id_slug_and_locale($subproject->id, $lang, $lang)) ) {
									$locale_obj = GP_Locales::by_slug($lang);
									$data = array();
									$data['name'] = $locale_obj->english_name;
									$data['slug'] = $lang;
									$data['locale'] = $lang;
									$data['project_id'] = $subproject->id;
									$new_ts = new GP_Translation_Set($data);
									GP::$translation_set->create($new_ts);
								}
							}
						}
						$message = sprintf(__('Import completed successfully with %d new and %d updated originals<br/>'), $this->originals_added, $this->originals_updated);
						foreach ( $this->translations_added as $lang => $added ) {
							$message .= sprintf(__('Added %d entries to [%s] translations<br/>'), $added, $lang);
						}
						foreach ( $this->import_errors as $error ) {
							$message .= "\n$error<br/>";
						}						
						$this->elgglp_success_and_import_form($message);
						return;
				}
			} else {
				$this->elgglp_error_and_import_form(__('Could not open the ZIP file you specified'));
				return;
			}
		}
		gp_tmpl_load('tools-elgg-import', get_defined_vars());
	}

	function elgg_export() {
		// anyone can export Elgg language packs
		//$this->logged_in_or_forbidden();
		// if it's a POST we need to export stuff
		if ( @$_POST['export']['gp_handle_settings'] == 'on' ) {
			// get the selection of projects and locales
			$elgg_cores = explode('|', @$_POST['export']['cores_selection']);
			$elgg_plugins = explode('|', @$_POST['export']['plugins_selection']);
			$elgg_locales = explode('|', @$_POST['export']['locales_selection']);
			// explode seems to put one element there if string is empty
			if ( @empty($elgg_cores[0]) ) $elgg_cores = array();
			if ( @empty($elgg_plugins[0]) ) $elgg_plugins = array();
			if ( @empty($elgg_locales[0]) ) $elgg_locales = array();
			// work out the name of the file to send to the browser as content-disposition
			$file2download = $export['elggpath'] ? $export['elggpath'] : 'elgg-languages.zip';
			if ( substr($file2download, -4) != '.zip' ) {
				$file2download .= '.zip';
			}
			// are original English texts to be included in language pack?
			$originals = ($export['originals'] == 'on');
			// should empty translation files to created in language pack?
			$export_empty = ($export['empty'] == 'on');
			// let's get information about the core project (use camelCase for object variables)
			$coreProject = GP::$project->by_slug('elgg');
			if ( !$coreProject ) {
				$this->elgglp_error_and_export_form(__('Core Elgg project could not be found'));
				return;
			}
			$filters['elgg_release'] = gp_retrieve_meta($coreProject->id, 'elgg_version', 'gp_project');
			unset($coreProject);
			// get the Elgg formatting object for exporting language files
			$this->format = GP::$formats['elgg'];
			// create the initial directory structure for the language pack
			$newdir = elgglp_tempdir();
			@mkdir("$newdir/languages", 0777, true);
			@mkdir("$newdir/install/languages", 0777, true);
			@mkdir("$newdir/mod", 0777, true);
			$filters['dst_dir'] = $newdir;
			unset($newdir);
			// this adds the plugin version automatically and the Elgg version from $filters['elgg_release']
			elgglp_create_languagepack_meta(null, $filters);

			// work through each subproject in elgg core
			$all_projects = array_merge($elgg_cores, $elgg_plugins);
			unset($elgg_cores);
			unset($elgg_plugins);
			foreach ( $all_projects as $projectid ) {
				// create the project object
				$subProject = GP::$project->get($projectid);
				// if not found, log error and continue
				if ( !$subProject ) {
					error_log("ElggExport: Could not open Elgg subproject $projectid in export loop");
				}
				// get the metadata for the project
				$meta = array();
				$meta['version'] = gp_retrieve_meta($subProject->id, 'elgg_plugin_version', 'gp_project');
				$meta['name'] = gp_retrieve_meta($subProject->id, 'elgg_plugin_name', 'gp_project');
				$meta['description'] = gp_retrieve_meta($subProject->id, 'elgg_plugin_description', 'gp_project');
				$meta['unique'] = gp_retrieve_meta($subProject->id, 'elgg_plugin_unique', 'gp_project');
				// work out the destination folder
				$dstdir = $filters['dst_dir'];
				if ( $meta['unique'] == 'install' ) {
					$dstdir = "$dstdir/install";
				} else if ( $meta['unique'] != 'core' ) {
					$dstdir = "$dstdir/mod/$meta[unique]";
				}
				// create directory if necessary
				@mkdir("$dstdir/languages", 0777, true);
				// export language files
				if ( $originals ) $this->elgglp_export_originals($subProject, $dstdir);
				$this->elgglp_export_languages($subProject, $dstdir, $elgg_locales, $export_empty);
				// create the languagemod.meta file
				elgglp_create_languagemod_meta($meta, $filters);
			}

			$zipfile = tempnam(sys_get_temp_dir(), 'elgglpzip');
			elgglp_zip_folder($filters['dst_dir'], $zipfile);
			elgglp_deltree($filters['dst_dir']);
			$this->headers_for_download($file2download);
			readfile($zipfile);
			unlink($zipfile);
			exit();
		}
		gp_tmpl_load('tools-elgg-export', get_defined_vars());
	}

	function elgglp_create_project($name, $slug, $desc, $parent = null) {
		$data = array();
		if ( $parent ) {
			$data['parent_project_id'] = $parent->id;
		} else {
			// make sure the slug of top level projects is unique
			$new_slug = $slug;
			while ( GP::$project->by_slug($new_slug) ) {
				$new_slug = "$slug-" . rand(1000, 9999);
			}
			$slug = $new_slug;
			unset($new_slug);
		}
		$data['name'] = $name;
		$data['slug'] = $slug;
		$data['description'] = $desc;
		$data['active'] = true;
		$new_project = new GP_Project($data);
		$project = GP::$project->create_and_select($new_project);
		if ( !$project ) {
			GP::$redirect_notices['error'] .= 'Could not create project ' . $name;
			return false;
		}
		return $project;
	}

	function elgglp_export_originals($proj, $basedir) {
		$rows = GP::$original->by_project_id($proj->id);
		$entries = array();
		foreach( (array)$rows as $row ) {
			$row->translations = array();
			$row->translations[] = $row->singular;
			$row->extracted_comment = $row->comment;
			$entries[] = new Translation_Entry((array)$row);
		}
		unset($rows);
		$locale = GP_Locales::by_slug('en');
		$set = new stdClass();
		$set->slug = 'en';
		$set->name = 'English';
		$langFile = $this->format->print_exported_file($proj, $locale, $set, $entries);
		file_put_contents("$basedir/languages/en.php", $langFile);
	}

	function elgglp_export_languages($proj, $basedir, $langs = array(), $export_empty = false) {
		$sets = GP::$translation_set->by_project_id($proj->id);
		foreach ( $sets as $set ) {
			if ( !empty($langs) && !in_array($set->locale, $langs) ) continue;
			if ( $export_empty || $set->current_count() > 0 ) {
				$entries = GP::$translation->for_export($proj, $set);
				$locale = GP_Locales::by_slug($set->locale);
				$langFile = $this->format->print_exported_file($proj, $locale, $set, $entries);
				file_put_contents("$basedir/languages/$locale->slug.php", $langFile);
			}
		}
	}

	function elgglp_error_and_export_form($message) {
		GP::$redirect_notices['error'] = $message;
		gp_tmpl_load('tools-elgg-export', get_defined_vars());
	}

	function elgglp_success_and_export_form($message) {
		GP::$redirect_notices['notice'] = $message;
		gp_tmpl_load('tools-elgg-export', get_defined_vars());
	}

	function elgglp_error_and_import_form($message) {
		GP::$redirect_notices['error'] = $message;
		gp_tmpl_load('tools-elgg-import', get_defined_vars());
	}

	function elgglp_success_and_import_form($message) {
		GP::$redirect_notices['notice'] = $message;
		gp_tmpl_load('tools-elgg-import', get_defined_vars());
	}

	function elgglp_import_languagemod($meta, $srcdir, $filters) {
		// copy meta and filter values used here into local variables
		$slug = $meta['unique'];
		$ignore_en = $filters['ignore_en'];
		$version = $filters['elgg_release'];
		// this creates the top level core project if necessary
		if ( !$this->coreProject ) {
			if ( $filters['coreproject'] ) {
				if ( !($this->coreProject = GP::$project->get($filters['coreproject'])) ) {
					GP::$redirect_notices['error'] = 'Could not find the project to import into';
					return false;
				}
			} else {
				$name = 'Elgg v' . $version;
				$desc = 'Social networking engine, delivering the building blocks that enable businesses, schools, universities and associations to create their own fully-featured social networks and applications';
				$newslug = 'elgg';
				if ( !($this->coreProject = $this->elgglp_create_project($name, $newslug, $desc)) ) {
					return false;
				}
			}
			gp_update_meta($this->coreProject->id, 'elgg_version', $version, 'gp_project');
		}
		// this creates the top level plugin project if necessary
		if ( !$this->pluginProject ) {
			if ( $filters['pluginproject'] ) {
				if ( !($this->pluginProject = GP::$project->get($filters['pluginproject'])) ) {
					GP::$redirect_notices['error'] = 'Could not find the project to import into';
					return false;
				}
			} else {
				$name = 'Third Party Plugins for Elgg';
				$desc = 'Plugins extend your Elgg site by adding additional functionality, languages and themes. They are contributed by members of the Elgg community.';
				$newslug = 'elgg3rd';
				if ( !($this->pluginProject = $this->elgglp_create_project($name, $newslug, $desc)) ) {
					return false;
				}
			}
			gp_update_meta($this->pluginProject->id, 'elgg_version', 'plugins', 'gp_project');
		}
		// first determine if this is a core or 3rd party plugin
		$cores = elgglp_core_plugins($version);
		if ( in_array($slug, $cores) ) {
			// it is a core plugin
			if ( !($projectObj = GP::$project->by_path("{$this->coreProject->slug}/$slug")) ) {
				$projectObj = $this->elgglp_create_project($meta['name'], $slug, $meta['description'], $this->coreProject);
			}
		} else {
			// it is a third party plugin
			$slug .= "---v$meta[version]";
			if ( !($projectObj = GP::$project->by_path("{$this->pluginProject->slug}/$slug")) ) {
				$projectObj = $this->elgglp_create_project($meta['name'], $slug, $meta['description'], $this->pluginProject);
			}
		}
		if ( $projectObj ) {
			gp_update_meta($projectObj->id, 'elgg_plugin_version', $meta['version'], 'gp_project');
			gp_update_meta($projectObj->id, 'elgg_plugin_name', $meta['name'], 'gp_project');
			gp_update_meta($projectObj->id, 'elgg_plugin_description', $meta['description'], 'gp_project');
			gp_update_meta($projectObj->id, 'elgg_plugin_unique', $meta['unique'], 'gp_project');
			$this->currentModProject = $projectObj;
			// prepare to import the English originals
			$file = "$srcdir/languages/en.php";
			// if should not ignore English and the language file for English exists...
			if ( !$ignore_en && file_exists($file) ) {
				// read originals from file
				$translations = $this->format->read_originals_from_file($file);
				// import into the current project
				list($originals_added, $originals_updated) = GP::$original->import_for_project($projectObj, $translations);
				// update import stats
				$this->originals_added += $originals_added;
				$this->originals_updated += $originals_updated;
			}
			// now go through all other language files in language mod
			elgglp_recurse_languages($meta, $srcdir, $filters, array($this, 'elgglp_import_languagefile'));
		} else {
			return false;
		}
	}

	function elgglp_import_languagefile($meta, $file, $lang, $filters) {
		// normalize language code so it works with GP_Locales object
		$lang = preg_replace_callback('/^([a-z]{2})[-_]([a-z]{2,3})$/i', create_function('$matches', 'return strtolower("$matches[1]-$matches[2]");'), $lang);
		// English originals have already been dealt with
		if ( $lang != 'en' ) {
			// import translations from language file
			$translations = $this->format->read_translations_from_file($file, $this->currentModProject);
			// is there an existing translation set for this language and project? if not, create one
			if ( !($ts = GP::$translation_set->by_project_id_slug_and_locale($this->currentModProject->id, $lang, $lang)) ) {
				$localeObj = GP_Locales::by_slug($lang);
				if ( $localeObj ) {
					$data = array();
					$data['name'] = $localeObj->english_name;
					$data['slug'] = $lang;
					$data['locale'] = $lang;
					$data['project_id'] = $this->currentModProject->id;
					$new_ts = new GP_Translation_Set($data);
					$ts = GP::$translation_set->create_and_select($new_ts);
				}
			}
			if ( $ts ) {
				// import translations into translation set
				$translations_added = $ts->import($translations);
				// update import stats
				$this->translations_added[$lang] += $translations_added;
			} else {
				$this->import_errors[] = sprintf(__('Could not create translations into %s for %s'), $lang, $meta['unique']);
			}
		}
	}
}