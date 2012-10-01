<?php
gp_title(__('Install', 'glotpress'));
gp_breadcrumb( array(
	'install' == $action? __('Install', 'glotpress') : __('Upgrade', 'glotpress'),
) );

$config_defaults = array(
	'gp_admin_username' => 'admin',
	'gp_admin_password' => 'a',
	'gp_admin_password2' => 'a'
);

$config = array_merge($config_defaults, $config);

gp_tmpl_header();
?>
<h2><?php echo wptexturize(sprintf(__('Installation Process (phase %d of %d)', 'glotpress'), 3, GP_TOT_INSTALL_PAGES)); ?></h2>

<dl>
	<dt><h3><?php echo __('GlotPress installed successfully', 'glotpress'); ?></h3></dt>
	<dd>
		<span><?php printf( __('The %s installation may be <a href="%s">accessed here</a>', 'glotpress'), gp_app_name(), gp_url_base() ); ?></span>
		<br/>
		<small><?php printf( __('Or you may <a href="%s">log in</a> to GlotPress straightaway with the username and password you have just created', 'glotpress'), gp_url_login() ); ?></small>
	</dd>
</dl>
	
<?php gp_tmpl_footer(); ?>