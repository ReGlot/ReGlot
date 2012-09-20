<?php
class GP {
	/**
	* @var GP_Project $project
	*/
	static $project;
	/**
	* @var GP_User $user
	*/
	static $user;
	/**
	* @var GP_Translation_Set $translation_set
	*/
	static $translation_set;
	/**
	* @var GP_Translation_Bundle $translation_bundle
	*/
	static $translation_bundle;
	/**
	* @var GP_Permission $permission
	*/
	static $permission;
	static $validator_permission;
	static $translation;
	/**
	* @var GP_Original $original
	*/
	static $original;
	// other singletons
	static $router;
	static $redirect_notices = array();
	static $translation_warnings;
	static $builtin_translation_warnings;
	static $current_route = null;
	static $formats;
	static $userauths;
	// plugins can use this space
	static $vars = array();
	// for plugin singletons
	static $plugins;
}
GP::$plugins = new stdClass();