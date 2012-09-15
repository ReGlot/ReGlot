<?php
class GP_Route_Tools extends GP_Route_Main {
	var $version;
	var $projects;
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
			$elggcoreproject = $_POST['import']['elggcoreproject'];
			$elgg3rdproject = $_POST['import']['elgg3rdproject'];
			$this->projects = array();
			$this->version = null;
			$this->parent_proj_id = null;
			$this->parent3rd_proj_id = null;
			$deltree = false;
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
					$deltree = true;
					$found = false;
					$dir = dir($newdir);
					while ( false !== ($entry = $dir->read()) ) {
						if ( $entry != '.' && $entry != '..' && is_dir("$newdir/$entry") ) {
							if ( $this->_check_elgg("$newdir/$entry", $version, $release) ) {
								$this->version = $release;
								$newdir = "$newdir/$entry";
								$found = true;
								break;
							}
						}
					}
			        $dir->close();
					if ( !$found ) {
						GP::$redirect_notices['error'] = 'Could not find an Elgg install in the ZIP file you specified';
					}
				} else {
					GP::$redirect_notices['error'] = 'Could not open the ZIP file you specified';
				}
			} else if ( @$_POST['import']['elggtype'] == 'dir' ) {
				$newdir = @$_POST['import']['elggpath'];
				if ( $this->_check_elgg($newdir, $version, $release) ) {
					$this->version = $release;
				} else {
					GP::$redirect_notices['error'] = 'Could not find an Elgg install in the folder you specified';
				}
			}
			if ( $deltree && @$_POST['import']['elggtype'] == 'zip' ) self::deltree($newdir);
			if ( !GP::$redirect_notices['error'] ) {
				$this->base_dir = $newdir;
				$this->_find_languages($newdir);
				if ( !empty($this->projects) ) {
					$format = GP::$formats['elgg'];
					if ( version_compare($this->version, '1.9.0-any') >= 0 ) {
						$format->version = 2;
					} else {
						$format->version = 1;
					}
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
						$this->topslug = 'elgg';
						$this->parent_proj_id = $this->_create_project($name, $this->topslug, $desc)->id;
					}
					if ( $this->parent_proj_id ) {
						gp_update_meta($this->parent_proj_id, 'elgg_version', file_get_contents("$newdir/version.php"), 'gp_project');
					}
					$cores = $this->core_plugins($this->version);
					foreach ( $this->projects as $slug => $project ) {
						if ( in_array($slug, $cores) ) {
							// Core Plugins
							if ( !($project_obj = GP::$project->by_path("$this->topslug/$slug")) ) {
								$project_obj = $this->_create_project($project['name'], $slug, $project['desc']);
							}
							if ( $project['manifest'] ) {
								gp_update_meta($project_obj->id, 'elgg_manifest', $project['manifest'], 'gp_project');
							} else {
								gp_delete_meta($project_obj->id, 'elgg_manifest', null, 'gp_project');
							}
						} else {
							// Third party plugins
							if ( !($project_obj = GP::$project->by_path("$this->top3rdslug/$slug")) ) {
								$project_obj = $this->_create_project3rd($project['name'], $slug, $project['desc']);
							}
						}
						// First, import originals, as they must be there when importing translations
						$file = $project['langs']['en'];
						if ( $file ) {
							// Import originals
							$translations = $format->read_originals_from_file($file);
							list($originals_added, $originals_existing) = GP::$original->import_for_project($project_obj, $translations);
							GP::$redirect_notices['notice'] .= sprintf(__('%s new originals were added, %s existing were updated for %s from %s.<br/>'), $originals_added, $originals_existing, $slug, $file);
						}
						
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
								}
								$ts->import($translations);
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
		gp_tmpl_load('tools-elgg-import', get_defined_vars());
	}

	function elgg_export() {
		$export = $_POST['export'];
		if ( @$export['gp_handle_settings'] == 'on' ) {
			$elggcoreproject = $export['elggcoreproject'];
			$elgg3rdproject = $export['elgg3rdproject'];
			$file2download = $export['elggpath'] ? $export['elggpath'] : 'elgg-languages.zip';
			if ( substr($file2download, -4) != '.zip' ) {
				$file2download .= '.zip';
			}
			$version = $export['version'];

			$coreProject = GP::$project->get($elggcoreproject);
			if ( !$coreProject ) {
				GP::$redirect_notices['error'] = 'Core Elgg project could not be found';
				gp_tmpl_load('tools-elgg-export', get_defined_vars());
			}

			if ( $elgg3rdproject ) {
				$thirdProject = GP::$project->get($elgg3rdproject);
				if ( !$thirdProject ) {
					GP::$redirect_notices['error'] = 'Third-party Elgg project could not be found';
					gp_tmpl_load('tools-elgg-export', get_defined_vars());
				}
			}

			$this->format = GP::$formats['elgg'];
			$this->format->version = $version;

			// create initial directory structure
			$newdir = $this->_tempdir();
			@mkdir("$newdir/languages", 0777, true);
			@mkdir("$newdir/install/languages", 0777, true);
			@mkdir("$newdir/mod", 0777, true);
			// create the version.php file
			$versionFile = gp_retrieve_meta($elggcoreproject, 'elgg_version', 'gp_project');
			file_put_contents("$newdir/version.php", $versionFile);

			// work through each subproject in elgg core
			$subprojects = $coreProject->sub_projects();
			foreach ( $subprojects as $subproject ) {
				if ( $subproject->slug == 'core' ) {
					$this->_export_originals($subproject, $newdir);
					$this->_export_languages($subproject, $newdir);
				} else if ( $subproject->slug == 'install' ) {
					$this->_export_originals($subproject, "$newdir/install");
					$this->_export_languages($subproject, "$newdir/install");
				} else {
					// create initial plugin directory structure
					$modpath = "$newdir/mod/$subproject->slug";
					@mkdir("$modpath/languages", 0777, true);
					// create the manifest.xml file
					$manifestFile = gp_retrieve_meta($subproject->id, 'elgg_manifest', 'gp_project');
					file_put_contents("$modpath/manifest.xml", $manifestFile);
					// export languages
					$this->_export_originals($subproject, $modpath);
					$this->_export_languages($subproject, $modpath);
				}
			}

			// if one was specified, work through each subproject in third party project
			if ( $thirdProject ) {
				$subprojects = $thirdProject->sub_projects();
				foreach ( $subprojects as $subproject ) {
					$pos = strpos($subproject->slug, '---v');
					if ( $pos ) {
						$slug = substr($subproject->slug, 0, pos);
					} else {
						$slug = $subproject->slug;
					}
					// create initial plugin directory structure
					$modpath = "$newdir/mod/$slug";
					@mkdir("$modpath/languages", 0777, true);
					// create the manifest.xml file
					$manifestFile = gp_retrieve_meta($subproject->id, 'elgg_manifest', 'gp_project');
					file_put_contents("$modpath/manifest.xml", $manifestFile);
					// export languages
					$this->_export_originals($subproject, $modpath);
					$this->_export_languages($subproject, $modpath);
				}
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

	function _check_elgg($dir, &$version, &$release) {
		if ( file_exists("$dir/install") && is_dir("$dir/install") &&
		  file_exists("$dir/languages") && is_dir("$dir/languages") &&
		  file_exists("$dir/mod") && is_dir("$dir/mod") &&
		  file_exists("$dir/version.php") && is_file("$dir/version.php") ) {
			include "$dir/version.php";
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
		if ( is_file($dirname) && preg_match('#^(?:(install)/|mod/([^/]*?)/)?languages/(\w\w\w?(?:-\w\w\w?)?)\.php$#', $subject, $matches) ) {
			$lang = $matches[3];
			if ( $matches[1] == '' && $matches[2] == '' ) {
				$slug = 'core';
				$name = 'Elgg Core';
				$desc = 'The core elements of the social networking engine';
				$manifest_contents = null;
			} else if ( $matches[2] == '' ) {
				$slug = $matches[1];
				$name = 'Elgg Install';
				$desc = 'Install wizard for setting up and configuring a new Elgg instance, or upgrading an existing one';
				$manifest_contents = null;
			} else {
				$slug = $matches[2];
				$manifest_name = dirname(dirname($dirname)) . '/manifest.xml';
				$manifest_contents = file_get_contents($manifest_name);
				try {
					$manifest = new SimpleXMLElement($manifest_contents);				
				} catch ( Exception $e ) {
					error_log("$manifest_name not valid", E_USER_ERROR);
					return;
				}
				$name = $manifest->name . ' v' . $manifest->version;
				$desc = (string)$manifest->description;
				if ( !in_array($slug, $cores) ) {
					$slug .= "---v$manifest->version";
					$slug = urlencode($slug);
				}
			}
			$this->projects[$slug]['name'] = $name;
			$this->projects[$slug]['desc'] = $desc;
			$this->projects[$slug]['langs'][$lang] = $dirname;
			if ( $manifest_contents ) $this->projects[$slug]['manifest'] = $manifest_contents;
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

	function _export_languages($proj, $basedir) {
		$sets = GP::$translation_set->by_project_id($proj->id);
		foreach ( $sets as $set ) {
			$entries = GP::$translation->for_export($proj, $set);
			$locale = GP_Locales::by_slug($set->locale);
			$langFile = $this->format->print_exported_file($proj, $locale, $set, $entries);
			file_put_contents("$basedir/languages/$locale->slug.php", $langFile);
		}
	}

}