<?php
/**
 * Second phase of GlotPress installation
 * 
 * @author Federico Mestrone, http://www.federicomestrone.com
 * 
 */

require_once('gp-load.php');
require_once(BACKPRESS_PATH . 'class.bp-sql-schema-parser.php');
require_once(GP_PATH . GP_INC . 'install-upgrade.php');
require_once(GP_PATH . GP_INC . 'schema.php');

if ( ($config = gp_post('config')) ) {
	$first = true;
	if ( defined('CUSTOM_USER_TABLE') ) {
		if ( empty($config['gp_wp_admin_user']) ) {
			GP::$redirect_notices['error'] = __('You must select a WordPress user as an administrator');
			$first = false;
		}
	} else {
		$required_other_config = array('gp_admin_username','gp_admin_password','gp_admin_password2','gp_admin_email');
		foreach ( $required_other_config as $key ) {
			if ( empty($config[$key]) ) {
				if ( $first ) {
					$first = false;
				} else {
					GP::$redirect_notices['error'] .= '<br/>';
				}
				GP::$redirect_notices['error'] .= sprintf(__('The configuration option "%s" cannot be empty'), $key);
			}
		}
		if ( $config['gp_admin_password'] !== $config['gp_admin_password2'] ) {
			if ( $first ) {
				$first = false;
			} else {
				GP::$redirect_notices['error'] .= '<br/>';
			}
			GP::$redirect_notices['error'] .= ('The password and the password confirmation do not match');
		}
	}
	if ( $first ) {
		if ( gp_create_initial_contents($config) ) {
			$install_uri = preg_replace('|/[^/]+?$|', '/', $_SERVER['PHP_SELF']) . 'install2.php';
			header('Location: ' . $install_uri);
			die();
		} else {
			GP::$redirect_notices['error'] .= __('Could not create the admin user or assign admin privileges');
		}
	}
} else {
	$config = array();
	if ( gp_get_option('gp_db_version') <= gp_get_option_from_db('gp_db_version') && !isset($_GET['force']) ) {
		GP::$redirect_notices['notice'] = __( 'You already have the latest version, no need to upgrade!' );
	} else {
		if ( gp_get( 'action', 'install' )  == 'upgrade' ) {
			$errors = gp_upgrade();
			if ( empty($errors) ) {
				GP::$redirect_notices['notice'] = __( 'GlotPress was successully upgraded!' );
			}
		} else {
			$errors = gp_install();
			if ( empty($errors) ) {
				GP::$redirect_notices['notice'] = __('GlotPress database and config were successully installed!');
			}
		}
		if ( !empty($errors) ) {
			GP::$redirect_notices['error'] = implode("<br/>", $errors);
		}
	}
}

// .htaccess included in distribution

$path = gp_add_slash(gp_url_path());
$action = gp_get('action', 'install');
gp_tmpl_load('install1',  get_defined_vars());