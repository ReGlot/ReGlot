<?php

/**
 * A class that defines the fields and methods of a GlotPress import/export format
 */

abstract class GP_UserAuth {
	protected $readonly;
	protected $name;

	function is_readonly() {
		return $this->readonly;
	}

	function get_name() {
		return $this->name;
	}
	
	abstract function new_user($args);

	abstract function get_user($user_or_id, $args = null);

	abstract function append_meta($args);
	
	abstract function update_meta($args);

	abstract function delete_meta($args);
}
