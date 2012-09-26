<?php
gp_title(__('Install'));
gp_breadcrumb( array(
	'install' == $action? __('Install') : __('Upgrade'),
) );

$config_defaults = array(
	'gp_admin_username' => 'admin',
	'gp_admin_password' => 'a',
	'gp_admin_password2' => 'a'
);

$config = array_merge($config_defaults, $config);

gp_tmpl_header();
?>

<h2><?php echo wptexturize(sprintf(__('Installation Process (phase %d of %d)'), 2, GP_TOT_INSTALL_PAGES)); ?></h2>
<form action="" method="post">

<dl>
	<dt><h3><?php echo __('Configure Administrator Options'); ?></h3></dt>
	<dd>
<?php if ( defined('CUSTOM_USER_TABLE') ) { ?>
		<label for="config[gp_wp_admin_user]">Administrator's Username</label>
		<input type="text" name="config[gp_wp_admin_user]" value="<?php echo $config['gp_wp_admin_user']; ?>" id="config[gp_wp_admin_user]"><br/>
		<small><?php _e('Select the WordPress user that will have administrator\'s rights in GlotPress'); ?></small>
<?php } else { ?>
		<label for="config[gp_admin_username]"><?php echo __('Admin Username'); ?></label>
		<input type="text" name="config[gp_admin_username]" value="<?php echo $config['gp_admin_username']; ?>" id="config[gp_admin_username]"><br/>
		<label for="config[gp_admin_name]"><?php echo __('Display Name'); ?></label>
		<input type="text" name="config[gp_admin_name]" value="<?php echo $config['gp_admin_name']; ?>" id="config[gp_admin_name]"><br/>
		<small><?php _e('The name that will be used for display on the website') ?></small><br/>
		<label for="config[gp_admin_password]"><?php echo __('Admin Password'); ?></label>
		<input type="password" name="config[gp_admin_password]" value="<?php echo $config['gp_admin_password']; ?>" id="config[gp_admin_password]"><br/>
		<label for="config[gp_admin_password2]"><?php echo __('Confirm Password'); ?></label>
		<input type="password" name="config[gp_admin_password2]" value="<?php echo $config['gp_admin_password2']; ?>" id="config[gp_admin_password2]"><br/>
		<label for="config[gp_admin_email]"><?php echo __('Email Address'); ?></label>
		<input type="text" name="config[gp_admin_email]" value="<?php echo $config['gp_admin_email']; ?>" id="config[gp_admin_email]"><br/>
		<small><?php _e('The default username is "admin", with a password of "a".'); ?></small><br/>
<?php } ?>
	</dd>
</dl>

<?php echo gp_js_focus_on('config[GPDB_NAME]'); ?>

	<p>
		<input type="submit" name="submit" value="<?php echo esc_attr(__('Finish')); ?>" id="submit" />
	</p>
</form>
	
<?php gp_tmpl_footer(); ?>