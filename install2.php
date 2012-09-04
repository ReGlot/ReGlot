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

// .htaccess included in distribution or needs to be created
if ( file_exists(GP_PATH . '.htaccess') ) {
	GP::$redirect_notices['notice'] = __('The <i>.htaccess</i> file already exists and has been left untouched. Make sure it contains the correct <i>mod_rewrite</i> configuration');
} else {
	$htaccess = <<<EOS
		# BEGIN GlotPress .
		RewriteEngine On
		RewriteBase /
		RewriteCond %{REQUEST_FILENAME} !-f
		RewriteCond %{REQUEST_FILENAME} !-d
		RewriteRule . /index.php [L]
		# END GlotPress
EOS;
		if ( file_put_contents(GP_PATH . '.htaccess', $htaccess) === false ) {
			GP::$redirect_notices['error'] = __('Could not create the <i>.htaccess</i> file for you. Make sure you create one with the following content');
			GP::$redirect_notices['error'] .= '<br/><pre>';
			GP::$redirect_notices['error'] .= $htaccess;
			GP::$redirect_notices['error'] .= '</pre>';
		}
}

$path = gp_add_slash(gp_url_path());
$action = gp_get('action', 'install');
gp_tmpl_load('install2',  get_defined_vars());