<?php
/**
 * Second phase of GlotPress installation
 * 
 * @author Federico Mestrone, http://www.federicomestrone.com
 * 
 */

require_once('gp-load.php');
require_once('gp-settings.php');

if ( ($config = gp_post('config')) ) {
} else {
	$config = array();
}

// .htaccess included in distribution

$path = gp_add_slash(gp_url_path());
$action = gp_get('action', 'install');
gp_tmpl_load('install1',  get_defined_vars());