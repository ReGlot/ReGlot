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

class ElggTranslatePlugin extends GP_Plugin {
    var $id = 'elggtranslate';

    function __construct() {
        parent::__construct();
        add_action('index', 'elggtranslate_homepage');
        add_action('gp_app_name', 'elggtranslate_app_name');
    }

    function init() {
        if ( $this->get_option('loaded') != 'yes' ) {
            // set up default format to Elgg
            gp_update_option('default_format', 'elgg');
            // flag that plugin has been loaded already
            $this->update_option('loaded', 'yes');
        }
    }
}

function elggtranslate_homepage() {
	include plugin_dir_path(__FILE__) . '/homepage.php';
}

function elggtranslate_app_name() {
	return 'ElggTranslate';
}

new ElggTranslatePlugin();