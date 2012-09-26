<?php
/*
Plugin Name: Name Of The Plugin
Plugin URI: http://URI_Of_Page_Describing_Plugin_and_Updates
Description: A brief description of the Plugin.
Version: The Plugin's Version Number, e.g.: 1.0
Author: Name Of The Plugin Author
Author URI: http://URI_Of_The_Plugin_Author
License: GPL2
*/

define('ELGGTRANSLATE_DIR', 'elggtranslate');

remove_all_actions('index');
//add_action('index', 'elggtranslate_homepage');
//add_action('gp_app_name', 'elggtranslate_app_name');

function elggtranslate_homepage() {
	include plugin_dir_path(__FILE__) . '/homepage.php';
}

function elggtranslate_app_name() {
	return 'ElggTranslate';
}
?>
