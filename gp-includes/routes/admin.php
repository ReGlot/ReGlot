<?php
class GP_Route_Admin extends GP_Route_Main {

	function users() {
		// this method is also invoked by ::delete(), ::admin(), and ::edit()
		if ( !$this->_admin_gatekeeper() ) return;
		$settings = $this->_save_setting(array('user_registration', 'public_home'));
		$users = GP::$user->all();
		gp_tmpl_load('users', get_defined_vars());
	}

	function register() {
		if ( gp_get_option('user_registration') != 'on' || (GP::$user && GP::$user->logged_in()) ) {
			$this->redirect(gp_url());
		} else {
			if ( $this->_manage_user(null) ) {
				$register = true;
				$user = new GP_User($_POST['user']);
				gp_tmpl_load('users_edit', get_defined_vars());
			} else {
				if ( GP::$redirect_notices['error'] ) {
					gp_notice_set(GP::$redirect_notices['error']);
				} else if ( GP::$redirect_notices['notice'] ) {
					gp_notice_set(GP::$redirect_notices['notice']);
				}
				$this->redirect(gp_url('/login'));
			}
		}
	}

	function edit($user_id) {
		if ( GP::$user->current()->id != $user_id && !$this->_admin_gatekeeper() ) return;
		if ( $this->_manage_user($user_id) ) {
			$user = GP::$user->get($user_id);
			if ( !user ) {
				$user = new GP_User($_POST['user']);
			}
			gp_tmpl_load('users_edit', get_defined_vars());
		} else {
			$this->users();
		}
	}

	function delete($user_id) {
		if ( !$this->_admin_gatekeeper() ) return;
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
		if ( !$this->_admin_gatekeeper() ) return;
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
		if ( !$this->_admin_gatekeeper() ) return;
		$settings = $this->_save_setting(array('default_format','default_recursive_sets'));
		gp_tmpl_load('settings', get_defined_vars());
	}

	function _save_setting($options) {
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

	// return true to stay in the user edit page
	// return false to go to user list page
	function _manage_user($user_id) {
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