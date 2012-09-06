<?php
class GP_Route_Admin extends GP_Route {

	function users() {
		$users = GP::$user->all();
		gp_tmpl_load('users', get_defined_vars());
	}

	function settings() {
		if ( ($settings = $_POST['settings']) ) {
			// TODO check for errors in the $gpdb->error variable or something
			gp_update_option('default_format', $settings['default_format']);
			GP::$redirect_notices['notice'] = __('Your settings have been saved');
		}
		$settings['gp_default_format'] = gp_get_option('default_format');
		gp_tmpl_load('settings', get_defined_vars());
	}

}