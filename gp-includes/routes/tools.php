<?php
class GP_Route_Tools extends GP_Route_Main {
	var $version;
	var $projects;
	var $all_langs;
	var $topslug;
	var $parent_proj_id;
	var $top3rdslug;
	var $parent3rd_proj_id;
	var $core_plugins;
	var $base_dir;
	var $format;

	function index() {
		gp_tmpl_load('tools', get_defined_vars());
	}

	function elgg_import() {
		if ( !$this->_admin_gatekeeper() ) return;
		if ( @$_POST['import']['gp_handle_settings'] == 'on' ) {
			@set_time_limit(300);
			$elggcoreproject = $_POST['import']['elggcoreproject'];
			$elgg3rdproject = $_POST['import']['elgg3rdproject'];
			$this->projects = array();
			$this->all_langs = array();
			$this->version = null;
			$this->parent_proj_id = null;
			$this->parent3rd_proj_id = null;
			if ( @$_POST['import']['elggtype'] == 'zip' ) {
				$file = $_FILES['elggfile']['tmp_name'];
				$zip = new ZipArchive;
				$res = $zip->open($file);
				if ( $res === true ) {
					$newdir = sys_get_temp_dir() . '/' . basename($file) . '_zzz';
					@mkdir($newdir);
					$zip->extractTo($newdir);
					$zip->close();
					unlink($file);
					if ( $this->_check_elgg($newdir, $elgg_version, $lp_version) ) {
						$this->version = $elgg_version;
					} else {
						GP::$redirect_notices['error'] = 'Could not find an Elgg install in the ZIP file you specified';
					}
				} else {
					GP::$redirect_notices['error'] = 'Could not open the ZIP file you specified';
				}
			} else if ( @$_POST['import']['elggtype'] == 'dir' ) {
				$newdir = @$_POST['import']['elggpath'];
				if ( $this->_check_elgg($newdir, $elgg_version, $lp_version) ) {
					$this->version = $elgg_version;
				} else {
					GP::$redirect_notices['error'] = 'Could not find an Elgg install in the folder you specified';
				}
			}
			if ( !GP::$redirect_notices['error'] ) {
				$this->base_dir = $newdir;
				$this->_find_languages($newdir);

				if ( !empty($this->projects) ) {
					$format = GP::$formats['elgg'];
					// create 3rd party first, so id of project is not there for _create_project method
					if ( $elgg3rdproject ) {
						if ( ($top3rdproj = GP::$project->get($elgg3rdproject)) ) {
							$this->parent3rd_proj_id = $top3rdproj->id;
							$this->top3rdslug = $top3rdproj->slug;
						} else {
							GP::$redirect_notices['error'] = 'Could not find the project to import into';
							$this->projects = array();
						}
					} else {
						$name = 'Third Party Plugins for Elgg';
						$desc = 'Plugins extend your Elgg site by adding additional functionality, languages and themes. They are contributed by members of the Elgg community.';
						$this->top3rdslug = 'elgg3rd';
						$this->parent3rd_proj_id = $this->_create_project($name, $this->top3rdslug, $desc)->id;
					}
					if ( $elggcoreproject ) {
						if ( ($topcoreproj = GP::$project->get($elggcoreproject)) ) {
							$this->parent_proj_id = $topcoreproj->id;
							$this->topslug = $topcoreproj->slug;
						} else {
							GP::$redirect_notices['error'] = 'Could not find the project to import into';
							$this->projects = array();
						}
					} else {
						$name = 'Elgg v' . $this->version;
						$desc = 'Social networking engine, delivering the building blocks that enable businesses, schools, universities and associations to create their own fully-featured social networks and applications';
						$this->topslug = 'elgg---v' . $this->version;
						$this->parent_proj_id = $this->_create_project($name, $this->topslug, $desc)->id;
					}
					if ( $this->parent_proj_id ) {
						gp_update_meta($this->parent_proj_id, 'elgg_version', $this->version, 'gp_project');
					}
					$cores = $this->core_plugins($this->version);
					foreach ( $this->projects as $slug => $project ) {
						if ( in_array($slug, $cores) ) {
							// Core Plugins
							if ( !($project_obj = GP::$project->by_path("$this->topslug/$slug")) ) {
								$project_obj = $this->_create_project($project['name'], $slug, $project['desc']);
							}
						} else {
							// Third party plugins
							$slug = $project['meta']['project_slug'];
							if ( !($project_obj = GP::$project->by_path("$this->top3rdslug/$slug")) ) {
								$project_obj = $this->_create_project3rd($project['name'], $slug, $project['desc']);
							}
						}
						gp_update_meta($project_obj->id, 'elgg_plugin_version', $project['meta']['version'], 'gp_project');
						gp_update_meta($project_obj->id, 'elgg_plugin_name', $project['meta']['name'], 'gp_project');
						gp_update_meta($project_obj->id, 'elgg_plugin_description', $project['meta']['description'], 'gp_project');
						gp_update_meta($project_obj->id, 'elgg_plugin_unique', $project['meta']['unique'], 'gp_project');
						// First, import originals, as they must be there when importing translations
						$file = $project['langs']['en'];
						if ( $file ) {
							// Import originals
							$translations = $format->read_originals_from_file($file);
							list($originals_added, $originals_existing) = GP::$original->import_for_project($project_obj, $translations);
							GP::$redirect_notices['notice'] .= sprintf(__("%s new originals were added, %s existing were updated for %s from %s.<br/>\n"), $originals_added, $originals_existing, $slug, $file);
						}

						$ts_added = '';
						$ts_created = '';
						foreach ( $project['langs'] as $locale => $file ) {
							if ( $locale != 'en' ) {
								// Import translations
								$translations = $format->read_translations_from_file($file, $project_obj);
								if ( !($ts = GP::$translation_set->by_project_id_slug_and_locale($project_obj->id, $locale, $locale)) ) {
									$locale_obj = GP_Locales::by_slug($locale);
									$data = array();
									$data['name'] = $locale_obj->english_name;
									$data['slug'] = $locale;
									$data['locale'] = $locale;
									$data['project_id'] = $project_obj->id;
									$new_ts = new GP_Translation_Set($data);
									$ts = GP::$translation_set->create_and_select($new_ts);
									if ( $ts_created == '' ) {
										$ts_created .= $locale;
									} else {
										$ts_created .= ", $locale";
									}
								} else {
									if ( $ts_added == '' ) {
										$ts_added .= $locale;
									} else {
										$ts_added .= ", $locale";
									}
								}
								if ( $ts ) {
									$ts->import($translations);
								} else {
									GP::$redirect_notices['notice'] .= sprintf(__("Could not create translation into %s for %s.<br/>\n"), $locale, $slug);
								}
							}
						}
						GP::$redirect_notices['notice'] .= sprintf(__("Created [%s], updated [%s] for %s.<br/>\n"), $ts_created, $ts_added, $slug);
					}
					$allsubprojects = GP::$project->get($this->parent_proj_id)->sub_projects();
					foreach ( $allsubprojects as $subproject ) {
						foreach ( $this->all_langs as $lang => $locale_obj ) {
							if ( $lang != 'en' && !($ts = GP::$translation_set->by_project_id_slug_and_locale($subproject->id, $lang, $lang)) ) {
								$data = array();
								$data['name'] = $locale_obj->english_name;
								$data['slug'] = $lang;
								$data['locale'] = $lang;
								$data['project_id'] = $subproject->id;
								$new_ts = new GP_Translation_Set($data);
								$ts = GP::$translation_set->create_and_select($new_ts);
							}
						}
					}
					$allsubprojects = GP::$project->get($this->parent3rd_proj_id)->sub_projects();
					foreach ( $allsubprojects as $subproject ) {
						foreach ( $this->all_langs as $lang => $locale_obj ) {
							if ( $lang != 'en' && !($ts = GP::$translation_set->by_project_id_slug_and_locale($subproject->id, $lang, $lang)) ) {
								$data = array();
								$data['name'] = $locale_obj->english_name;
								$data['slug'] = $lang;
								$data['locale'] = $lang;
								$data['project_id'] = $subproject->id;
								$new_ts = new GP_Translation_Set($data);
								$ts = GP::$translation_set->create_and_select($new_ts);
							}
						}
					}
//					if ( !GP::$redirect_notices['error'] ) {
//						GP::$redirect_notices['notice'] .= 'File ' . $file . ' successfully imported to ' . sys_get_temp_dir();
//					}
				} else {
					GP::$redirect_notices['notice'] = 'Could not find any translations in your package';
				}
			}
		}
		if ( @$_POST['import']['elggtype'] == 'zip' ) self::deltree($newdir);
		gp_tmpl_load('tools-elgg-import', get_defined_vars());
	}

	function elgg_export() {
		$export = $_POST['export'];
		if ( @$export['gp_handle_settings'] == 'on' ) {

			// get the selection of projects and locales
			$elgg_cores = explode('|', $export['cores_selection']);
			$elgg_plugins = explode('|', $export['plugins_selection']);
			$elgg_locales = explode('|', $export['locales_selection']);

			// explode seems to put one element there if string is empty
			if ( @empty($elgg_cores[0]) ) $elgg_cores = array();
			if ( @empty($elgg_plugins[0]) ) $elgg_plugins = array();
			if ( @empty($elgg_locales[0]) ) $elgg_locales = array();

			// work out the name of the file to send to the browser as content-disposition
			$file2download = $export['elggpath'] ? $export['elggpath'] : 'elgg-languages.zip';
			if ( substr($file2download, -4) != '.zip' ) {
				$file2download .= '.zip';
			}

			// version not needed at the moment
			// $version = $export['version'];

			// are original English texts to be included in language pack?
			$originals = ($export['originals'] == 'on');

			// should empty translation files to created in language pack?
			$export_empty = ($export['empty'] == 'on');

			// let's get information about the core project (use camelCase for object variables)
			$coreProject = GP::$project->by_path('elgg---v1.8.8');
			if ( !$coreProject ) {
				GP::$redirect_notices['error'] = 'Core Elgg project could not be found';
				gp_tmpl_load('tools-elgg-export', get_defined_vars());
				exit;
			}

			// get the Elgg formatting object for exporting language files
			$this->format = GP::$formats['elgg'];

			// create the initial directory structure for the language pack
			$newdir = $this->_tempdir();
			@mkdir("$newdir/languages", 0777, true);
			@mkdir("$newdir/install/languages", 0777, true);
			@mkdir("$newdir/mod", 0777, true);

			// create the languagepack.meta file
			$meta = array();
			$meta['elgg_version'] = gp_retrieve_meta($coreProject->id, 'elgg_version', 'gp_project');
			$meta['languagepack_version'] = '1.0';
			file_put_contents("$newdir/languagepack.meta", json_encode($meta));

			// work through each subproject in elgg core
			$all_projects = array_merge($elgg_cores, $elgg_plugins);
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
				if ( $meta['unique'] == 'core' ) {
					// this is the core Elgg language file
					$modpath = $newdir;
				} else if ( $meta['unique'] == 'install' ) {
					// this is the Elgg installation language file
					$modpath = "$newdir/install";
				} else {
					// create the initial plugin directory structure
					$modpath = "$newdir/mod/$meta[unique]";
					@mkdir("$modpath/languages", 0777, true);
				}
				// export language files
				if ( $originals ) $this->_export_originals($subProject, $modpath);
				$this->_export_languages($subProject, $modpath, $elgg_locales, $export_empty);
				// create the languagemod.meta file
				file_put_contents("$modpath/languages/languagemod.meta", json_encode($meta));
			}

			$zipfile = tempnam(sys_get_temp_dir(), 'zip');
			$this->_zip_folder($newdir, $zipfile);
			self::deltree($newdir);
			$this->headers_for_download($file2download);
			readfile($zipfile);
			unlink($zipfile);
			exit();
		}
		gp_tmpl_load('tools-elgg-export', get_defined_vars());
	}

	function _create_project($name, $slug, $desc) {
		$data = array();
		$data['name'] = $name;
		$data['slug'] = $slug;
		$data['description'] = $desc;
		$data['parent_project_id'] = $this->parent_proj_id;
		$data['active'] = true;
		$new_project = new GP_Project($data);
		$project = GP::$project->create_and_select($new_project);
		if ( !$project ) {
			GP::$redirect_notices['error'] .= 'Could not create project ' . $name;
		}
		return $project;
	}

	function _create_project3rd($name, $slug, $desc) {
		$data = array();
		$data['name'] = $name;
		$data['slug'] = $slug;
		$data['description'] = $desc;
		$data['parent_project_id'] = $this->parent3rd_proj_id;
		$data['active'] = true;
		$new_project = new GP_Project($data);
		$project = GP::$project->create_and_select($new_project);
		if ( !$project ) {
			GP::$redirect_notices['error'] .= 'Could not create project ' . $name;
		}
		return $project;
	}

	function _check_elgg($dir, &$elgg_version, &$languagepack_version) {
		if ( file_exists("$dir/install") && is_dir("$dir/install") &&
		  file_exists("$dir/languages") && is_dir("$dir/languages") &&
		  file_exists("$dir/mod") && is_dir("$dir/mod") &&
		  file_exists("$dir/languagepack.meta") && is_file("$dir/languagepack.meta") ) {
			$meta = json_decode(file_get_contents("$dir/languagepack.meta"), true);
			$elgg_version = $meta['elgg_version'];
			$languagepack_version = $meta['languagepack_version'];
			return true;
		}
		return false;
	}

	function _find_languages($dirname) {
		$cores = $this->core_plugins($this->version);
		if ( !$cores ) {
			GP::$redirect_notices['error'] = "Elgg Version $this->version not supported";
			return;
		}
        if ( !file_exists($dirname) || is_link($dirname) ) { return; }
		$subject = str_replace($this->base_dir, '', $dirname);
		if ( $subject[0] == '/' ) $subject = substr ($subject, 1);
		if ( is_file($dirname) && preg_match('#^(?:(install)/|mod/([^/]*?)/)?languages/(\w\w\w?)(?:[_-](\w\w\w?))?\.php$#', $subject, $matches) ) {
			$lang = $matches[3];
			if ( $matches[4] ) {
				$lang .= '-' . $matches[4];
			}
			$lang = strtolower($lang);
			if ( $matches[2] == '' ) {
				if ( $matches[1] == '' ) {
					$slug = 'core';
					$name = 'Elgg Core';
					$desc = 'The core elements of the social networking engine';
				} else {
					$slug = $matches[1];
					$name = 'Elgg Install';
					$desc = 'Install wizard for setting up and configuring a new Elgg instance, or upgrading an existing one';
				}
				if ( !$this->projects[$slug] ) {
					$meta = array(
						'version' => $this->version,
						'description' => $desc,
						'name' => $name,
					);
				}
			} else {
				$slug = $matches[2];
				$meta = null;
				if ( !$this->projects[$slug] ) {
					$meta_name = dirname($dirname) . '/languagemod.meta';
					$meta_contents = file_get_contents($meta_name);
					$meta = json_decode($meta_contents, true);
					$name = $meta['name'] . ' v' . $meta['version'];
					$desc = (string)$meta['description'];
					if ( !in_array($slug, $cores) ) {
						$projslug = "$slug---v$meta[version]";
						$projslug = urlencode($projslug);
					}
				}
			}
			$locale_obj = GP_Locales::by_slug($lang);
			if ( $locale_obj ) {
				if ( $lang != 'en' ) $this->all_langs[$lang] = $locale_obj;
				if ( !$this->projects[$slug] ) {
					$this->projects[$slug]['name'] = $name;
					$this->projects[$slug]['desc'] = $desc;
					if ( $meta ) {
						$this->projects[$slug]['meta']['version'] = $meta['version'];
						$this->projects[$slug]['meta']['description'] = $meta['description'];
						$this->projects[$slug]['meta']['name'] = $meta['name'];
						$this->projects[$slug]['meta']['unique'] = $slug;
						$this->projects[$slug]['meta']['project_slug'] = $projslug;
					}
				}
				$this->projects[$slug]['langs'][$lang] = $dirname;
			} else {
				error_log("Do not know locale $lang in $name for file $dirname");
			}
            return;
        } else if ( is_file($dirname) || is_link($dirname) ) {
			return;
		}

        $dir = dir($dirname);
        while ( false !== ($entry = $dir->read()) ) {
            if ( $entry[0] == '.' ) {
                continue; 
            } 
            $this->_find_languages("$dirname/$entry");
        }
        $dir->close();
        return;
    }

	function core_plugins($version) {
		if ( !$this->core_plugins ) {
			$this->core_plugins = array(
				'1.8.8' => array(
					'core', 'install', // these ones are not real Elgg plugins
					'blog', 'bookmarks', 'categories', 'custom_index', 'dashboard', 'developers', 'diagnostics', 'embed',
					'externalpages', 'file', 'garbagecollector', 'groups', 'invitefriends', 'likes', 'logbrowser', 'logrotate',
					'members', 'messageboard', 'messages', 'notifications', 'oauth_api', 'pages', 'profile', 'reportedcontent',
					'search', 'tagcloud', 'thewire', 'tinymce', 'twitter', 'twitter_api', 'uservalidationbyemail', 'zaudio'
				)
			);
			$this->core_plugins['1.8.6'] = $this->core_plugins['1.8.8'];
			$this->core_plugins['1.9.0-dev'] = array_merge($this->core_plugins['1.8.8'], array('languagepacks'));
		}
		return $this->core_plugins[$version];
	}

	function _tempdir($dir = false, $prefix = '') {
		if ( !$dir ) $dir = sys_get_temp_dir();
		$tempfile = tempnam($dir, $prefix);
		if ( file_exists($tempfile) ) { unlink($tempfile); }
		mkdir($tempfile);
		if ( is_dir($tempfile) ) { return $tempfile; }
	}

	function _zip_folder($source, $destination) {
		$zip = new ZipArchive();
		if ( !$zip->open($destination, ZIPARCHIVE::CREATE) ) {
			return false;
		}

		$source = str_replace('\\', '/', realpath($source));

		if ( is_dir($source) === true )
		{
			$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);

			foreach ( $files as $file )
			{
				$file = str_replace('\\', '/', $file);

				// Ignore all hidden files and folders
				if ( $file[0] == '.' )
					continue;

				$file = realpath($file);

				if ( is_dir($file) === true )
				{
					$zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
				}
				else if (is_file($file) === true)
				{
					$zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
				}
			}
		}
		else if (is_file($source) === true)
		{
			$zip->addFromString(basename($source), file_get_contents($source));
		}

		return $zip->close();
	}

	function _export_originals($proj, $basedir) {
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

	function _export_languages($proj, $basedir, $langs = array(), $export_empty = false) {
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

}