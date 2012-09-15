<?php

if ( !class_exists( 'WP_Users' ) ) {
	require_once( BACKPRESS_PATH . 'class.wp-users.php' );
	$wp_users_object = new WP_Users( $gpdb );
}

class GP_UserAuth_WordPress extends GP_UserAuth {
	protected $name = 'WordPress Tables';
	protected $readonly = false;

	public function append_meta($args) {
		global $wp_users_object;
		return $wp_users_object->append_meta($args);
	}

	public function delete_meta($args) {
		global $wp_users_object;
		return $wp_users_object->delete_meta($args);
	}

	public function get_user($user_or_id, $args = null) {
		global $wp_users_object;
		return $wp_users_object->get_user($user_or_id, $args = null);
	}

	public function new_user($args) {
		global $wp_users_object;
		return $wp_users_object->new_user($args);
	}

	public function update_meta($args) {
		global $wp_users_object;
		return $wp_users_object->update_meta($args);
	}
}

GP::$userauths['wordpress'] = new GP_UserAuth_WordPress;