<?php
/**
 * Landing point for GlotPress installation
 * 
 * @author Federico Mestrone, http://www.federicomestrone.com
 * 
 */

define('GP_INSTALLING', true);
define('GP_DEBUG', true);

require_once('gp-load.php');
require_once('gp-settings.php');

if ( ($config = gp_post('config')) ) {
	$first = true;

	$required_db_config = array('GPDB_NAME','GPDB_USER','GPDB_PASSWORD','GPDB_HOST','GPDB_CHARSET','GPDB_COLLATE','gp_table_prefix');
	foreach ( $required_db_config as $key ) {
		if ( empty($config[$key]) ) {
			if ( $first ) {
				$first = false;
			} else {
				GP::$redirect_notices['error'] .= '<br/>';
			}
			GP::$redirect_notices['error'] .= sprintf(__('The database configuration field "%s" cannot be empty'), $key);
		}
	}

	$required_key_config = array('GP_AUTH_KEY','GP_SECURE_AUTH_KEY','GP_LOGGED_IN_KEY','GP_NONCE_KEY');
	foreach ( $required_key_config as $key ) {
		if ( empty($config[$key]) ) {
			if ( $first ) {
				$first = false;
			} else {
				GP::$redirect_notices['error'] .= '<br/>';
			}
			GP::$redirect_notices['error'] .= __('The authentication keys must be set. You can generate them on the WordPress API site.');
			break;
		}
	}

	if ( $config['gp_enable_wordpress_users'] == 'on' ) {
		$required_wpusers_config = array('CUSTOM_USER_TABLE','CUSTOM_USER_META_TABLE');
		foreach ( $required_wpusers_config as $key ) {
			if ( empty($config[$key]) ) {
				if ( $first ) {
					$first = false;
				} else {
					GP::$redirect_notices['error'] .= '<br/>';
				}
				GP::$redirect_notices['error'] .= __('If you enable WordPress authentication, you must specify user and meta table as well as the admin user.');
				break;
			}
		}
	}
	
	if ( $first ) {
		$config_file = file_get_contents(GP_PATH . 'gp-config-sample.php');
		foreach ( $required_db_config as $key ) {
			$config_file = preg_replace("/<<$key>>/", $config[$key], $config_file);
		}
		foreach ( $required_key_config as $key ) {
			$config_file = preg_replace("/<<$key>>/", $config[$key], $config_file);
		}
		if ( $config['gp_enable_wordpress_users'] == 'on' ) {
			$config_file = preg_replace('|//|', '', $config_file);
			foreach ( $required_wpusers_config as $key ) {
				$config_file = preg_replace("/<<$key>>/", $config[$key], $config_file);
			}
		}
		$config_file = preg_replace('/<<GP_LANG>>/', $config['GP_LANG'], $config_file);
		if ( file_put_contents(GP_PATH . 'gp-config.php', $config_file) === false ) {
			GP::$redirect_notices['error'] .= __('Cannot save the config file. Please change the permissions on the folder or create the <i>gp-config.php</i> file with this content');
			GP::$redirect_notices['error'] .= '<br/>' . $config_file;
		} else {
			$install_uri = preg_replace('|/[^/]+?$|', '/', $_SERVER['PHP_SELF']) . 'install1.php';
			header('Location: ' . $install_uri);
			die();
		}
	}
} else {
	$config = array();
}

gp_tmpl_load('install', get_defined_vars());