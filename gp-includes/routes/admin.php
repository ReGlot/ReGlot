<?php
class GP_Route_Admin extends GP_Route_Main {

    function tools() {
        $tools_config = apply_filters('gp_tools', array());
        gp_tmpl_load('tools', get_defined_vars());
    }

    // this method is also invoked by ::delete(), ::admin(), and ::edit() when they complete their task
	function users() {
		// only logged-in users have access to this
		$this->logged_in_or_forbidden();
		// save settings avaible on users page
		$settings = $this->_save_setting(array('user_registration', 'public_home'));
		// load list of users
		$users = GP::$user->all();
		// open users template and pass all defined variables ($users and $settings) into it
		gp_tmpl_load('users', get_defined_vars());
	}

	function register() {
		// this page is forbidden if registration is not enabled or a user is logged in
		$this->forbidden_if(gp_get_option('user_registration') != 'on' || (GP::$user && GP::$user->logged_in()));
		// handles POST of user data if POST request
		if ( $this->_manage_user(null) ) {
			// if true, need to show the user_edit template (again)
			$register = true;
			$user = new GP_User($_POST['user']);
			gp_tmpl_load('users_edit', get_defined_vars());
		} else {
			// if false, can move on to login page, with error or new user
			if ( GP::$redirect_notices['error'] ) {
				gp_notice_set(GP::$redirect_notices['error']);
			} else if ( GP::$redirect_notices['notice'] ) {
				gp_notice_set(GP::$redirect_notices['notice']);
			}
			$this->redirect(gp_url('/login'));
		}
	}

	function edit($user_id) {
		// this page is forbidden if user is not editing their own profile and user is not an admin
		$this->forbidden_if(GP::$user->current()->id != $user_id && !GP::$user->current()->admin());
		// handles POST of user data if POST request
		if ( $this->_manage_user($user_id) ) {
			// if true, need to show the user_edit template (again)
			$user = GP::$user->get($user_id);
			if ( !user ) {
				$user = new GP_User($_POST['user']);
			}
			gp_tmpl_load('users_edit', get_defined_vars());
		} else {
			// if false, can move on to user list page, with error or new user
			$this->users();
		}
	}

	function delete($user_id) {
		// only admin can do this
		$this->admin_or_forbidden();
		$user = GP::$user->get($user_id);
		if ( $user ) {
			if ( $user->admin() && GP::$permission->count_admins() <= 1 ) {
				GP::$redirect_notices['error'] = __('There must be at least one admin for this site');
			} else {
				$user->delete();
				GP::$redirect_notices['notice'] = __('The user has been deleted from the system');
				if ( $user->id == GP::$user->current()->id ) {
					GP::$redirect_notices['notice'] .= __('<br/>You won\'t be able to log back in to the site once you log out');
				}
			}
		} else {
			GP::$redirect_notices['error'] = __('Cannot find the user specified');
		}
		$this->users();
	}

	function admin($user_id) {
		// only admin can do this
		$this->admin_or_forbidden();
		$user = GP::$user->get($user_id);
		if ( $user ) {
			if ( $user->admin() ) {
				if ( GP::$permission->count_admins() <= 1 ) {
					GP::$redirect_notices['error'] = __('There must be at least one admin for this site');
				} else {
					$permission = GP::$permission->find_one(array('user_id' => $user->id, 'action' => 'admin'));
					$permission->action = null;
					$permission->save();
					GP::$redirect_notices['notice'] = __('Admin privileges for the user have been revoked');
					if ( $user->id == GP::$user->current()->id ) {
						GP::$redirect_notices['notice'] .= __('<br/>You don\'t have admin privileges anymore as of this moment');
					}
				}
			} else {
				$permission = GP::$permission->find_one(array('user_id' => $user->id, 'action' => null));
				if ( $permission ) {
					$permission->action = 'admin';
					$permission->save();
				} else {
					GP::$permission->create(array('user_id' => $user->id, 'action' => 'admin'));
				}
				GP::$redirect_notices['notice'] = __('The user has been made an admin of this site');
			}
		} else {
			GP::$redirect_notices['error'] = __('Cannot find the user specified');
		}
		$this->users();
	}

	function settings() {
		// only admin can do this
		$this->admin_or_forbidden();
		// save settings avaible on settings page
		$settings = $this->_save_setting(array('default_format','default_recursive_sets'));
		// open settings template and pass all defined variables ($settings) into it
		gp_tmpl_load('settings', get_defined_vars());
	}

	private function _save_setting($options) {
		$settings = $_POST['settings'];
		if ( $settings['gp_handle_settings'] ) {
			if ( !is_array($options) ) { $options = array($options); };
			foreach ( $options as $option ) {
				gp_update_option($option, $settings[$option]);
			}
			GP::$redirect_notices['notice'] = __('Your settings have been saved');
		}
		$settings = array();
		if ( is_array($options) ) { // avoid PHP warning
			foreach ( $options as $option ) {
				$settings[$option] = gp_get_option($option);
			}
		}
		return $settings;
	}

	// returns true to stay in the user edit page
	// returns false to go to user list page
	private function _manage_user($user_id) {
		$settings = $_POST['user'];
		if ( $settings['gp_handle_settings'] ) {
			if ( $user_id ) {
				$user = GP::$user->get($user_id);
				if ( !$user ) {
					GP::$redirect_notices['error'] = __('Cannot find the user specified');
					return false;
				}
				global $wp_users_object;
				if ( $settings['user_pass'] ) {
					if ( $settings['user_pass'] == $settings['user_pass2'] ) {
						$wp_users_object->set_password($settings['user_pass'], $user_id);
					} else {
						GP::$redirect_notices['error'] = __('Password confirmation does not match');
						return true;
					}
				}
				$userargs = array();
				$userargs['user_login'] = $settings['user_login'];
				$userargs['user_email'] = $settings['user_email'];
				$userargs['user_url'] = $settings['user_url'];
				$userargs['display_name'] = $settings['display_name'];
				$wp_users_object->update_user($user_id, $userargs);
				GP::$redirect_notices['notice'] = __('User profile successfully updated');
				return true;
			} else {
				if ( $settings['user_pass'] != $settings['user_pass2'] ) {
					GP::$redirect_notices['error'] = __('Password confirmation does not match');
					return true;
				}
				if ( empty($settings['user_pass']) || empty($settings['user_login']) || empty($settings['user_email']) ) {
					GP::$redirect_notices['error'] = __('You must provide username and email, please');
					return true;
				}
				$user = GP::$user->create(array(
					'user_login' => $settings['user_login'],
					'user_pass' => $settings['user_pass'],
					'user_email' => $settings['user_email'],
					'user_url' => $settings['user_url'],
					'display_name' => $settings['display_name'])
				);
				if ( $user ) {
					GP::$redirect_notices['notice'] = __('User profile successfully created');
					return false;
				} else {
					GP::$redirect_notices['error'] = __('User profile could not be created');
					return true;
				}
			}
		} else {
			return true;
		}
	}

}