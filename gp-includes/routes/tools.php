<?php
class GP_Route_Tools extends GP_Route_Main {
	var $version;
	var $projects;
	var $parent_proj_id;

	function index() {
		if ( !$this->_admin_gatekeeper() ) return;
		gp_tmpl_load('tools', get_defined_vars());
	}

	function elgg_import() {
		if ( @$_POST['import']['gp_handle_settings'] == 'on' ) {
			$file = $_FILES['elggfile']['tmp_name'];
			$zip = new ZipArchive;
			$res = $zip->open($file);
			if ( $res === true ) {
				$newdir = sys_get_temp_dir() . $file . '_zzz';
				mkdir($newdir);
				$zip->extractTo($newdir);
				$zip->close();
				$this->projects = array();
				$this->_find_languages($newdir);
				$this->parent_proj_id = null;
				if ( !empty($this->projects) ) {
					$format = GP::$formats['elgg'];
					$name = 'Elgg v' . $this->version;
					$desc = 'Social networking engine, delivering the building blocks that enable businesses, schools, universities and associations to create their own fully-featured social networks and applications';
					$slug = 'elgg';
					$this->parent_proj_id = $this->_create_project($name, $slug, $desc)->id;
					foreach ( $this->projects as $slug => $project ) {
						$project_obj = $this->_create_project($project['name'], $slug, $project['desc']);
						foreach ( $project['langs'] as $locale => $file ) {
							if ( $locale == 'en' ) {
								$translations = $format->read_originals_from_file($file);
								list($originals_added, $originals_existing) = GP::$original->import_for_project($project_obj, $translations);
								GP::$redirect_notices['notice'] .= sprintf(__('%s new strings were added, %s existing were updated for %s.<br/>'), $originals_added, $originals_existing, $slug);
							}
						}

					}
					if ( !GP::$redirect_notices['notice'] ) {
						GP::$redirect_notices['notice'] = 'File ' . $file . ' successfully imported to ' . sys_get_temp_dir();
					}
				} else {
					GP::$redirect_notices['notice'] = 'Could not find any translations in your package';
				}
			} else {
				GP::$redirect_notices['error'] = 'Could not extract' . $file . ' to ' . sys_get_temp_dir();
			}
			unlink($file);
			self::deltree($dirname);
		}
		if ( !$this->_admin_gatekeeper() ) return;
		gp_tmpl_load('tools_elgg_import', get_defined_vars());
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

	function _find_languages($dirname) {
        if ( !file_exists($dirname) || is_link($dirname) ) { return; }

		if ( is_file($dirname) && preg_match('#elgg-([^/]*?)/(?:(install)/|mod/([^/]*?)/)?languages/(\w\w\w?(?:-\w\w\w?)?)\.php$#', $dirname, $matches) ) {
			if ( $matches[2] == '' && $matches[3] == '' ) {
				$this->version = $matches[1];
				$matches[3] = 'core';
				$name = 'Elgg Core v' . $this->version;
				$desc = 'The core elements of the social networking engine';
			} else if ( $matches[3] == '' ) {
				$matches[3] = $matches[2];
				$name = 'Elgg Install';
				$desc = 'Install wizard for setting up and configuring a new Elgg instance, or upgrading an existing one';
			} else {
				$manifest_name = dirname(dirname($dirname)) . '/manifest.xml';
				$manifest_contents = file_get_contents($manifest_name);
				$manifest = new SimpleXMLElement($manifest_contents);
				$name = $manifest->name . ' v' . $manifest->version;
				$desc = (string)$manifest->description;
			}
			$this->projects[$matches[3]]['name'] = $name;
			$this->projects[$matches[3]]['desc'] = $desc;
			$this->projects[$matches[3]]['langs'][$matches[4]] = $dirname;
            return;
        } else if ( is_file($dirname) || is_link($dirname) ) {
			return;
		}

        $dir = dir($dirname);
        while ( false !== ($entry = $dir->read()) ) {
            if ( $entry == '.' || $entry == '..' ) { 
                continue; 
            } 
            $this->_find_languages("$dirname/$entry");
        }
        $dir->close();
        return;
    }

}