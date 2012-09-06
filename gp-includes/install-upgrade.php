<?php
/**
 * Guesses the final installed URI based on the location of the install script
 *
 * @return string The guessed URI
 */
function guess_uri()
{
	$schema = 'http://';
	if ( strtolower( gp_array_get( $_SERVER, 'HTTPS' ) ) == 'on' ) {
		$schema = 'https://';
	}
	$uri = preg_replace( '|/[^/]*$|i', '/', $schema . gp_array_get( $_SERVER, 'HTTP_HOST') . gp_array_get( $_SERVER, 'REQUEST_URI' ) );

	return rtrim( $uri, " \t\n\r\0\x0B/" ) . '/';
}

function gp_update_db_version() {
	gp_update_option( 'gp_db_version', gp_get_option( 'gp_db_version' ) );
}

function gp_upgrade_db() {
	global $gpdb;
	
	$alterations = BP_SQL_Schema_Parser::delta( $gpdb, gp_schema_get() );
	$messages = $alterations['messages'];
	$errors = $alterations['errors'];
	if ( $errors ) return $errors;
	
	gp_upgrade_data( gp_get_option_from_db( 'gp_db_version' ) );

	gp_update_db_version();
}

function gp_upgrade() {
    return gp_upgrade_db();
}

function gp_upgrade_data( $db_version ) {
	global $gpdb;
	if ( $db_version < 190 ) {
		$gpdb->query("UPDATE $gpdb->translations SET status = REPLACE(REPLACE(status, '-', ''), '+', '');");
	}
}

function gp_install() {
    global $gpdb;
    
    $errors = gp_upgrade_db();
    
	if ( $errors ) return $errors;
	
	gp_update_option( 'uri', guess_uri() );
}

function gp_create_initial_contents($options) {	
	global $gpdb;
	
	if ( !defined('CUSTOM_USER_TABLE') ) {
		$admin = GP::$user->create(array('user_login' => $options['gp_admin_username'], 'user_pass' => $options['gp_admin_password'], 'user_email' => $options['gp_admin_email'], 'display_name' => $options['display_name']));
		GP::$permission->create(array('user_id' => $admin->id, 'action' => 'admin'));
		return true;
	} else {
		if ( ($admin = GP::$user->by_login($options['gp_wp_admin_user'])) ) {
			GP::$permission->create(array('user_id' => $admin->id, 'action' => 'admin'));
			return true;
		} else {
			return false;
		}
	}
	gp_update_option('default_format', 'po');
	gp_update_option('user_registration', 'on');
	gp_update_option('public_home', 'on');
}