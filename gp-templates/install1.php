<?php
gp_title( __('Install &lt; GlotPress') );
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

<?php if ($errors): ?>
	<?php _e('There were some errors:'); ?>
	<pre>
		<?php echo implode("\n", $errors); ?>
	</pre>
<?php 
	else:
?>
<?
		echo $success_message;
?>
<h2><?php echo wptexturize(__('Last Installation Options')); ?></h2>
<form action="" method="post">

<dl>
	<dt><h3><?php echo __('Configure Administrator Options'); ?></h3></dt>
	<dd>
		<label for="config[gp_admin_username]"><?php echo __('Admin Username'); ?></label>
		<input type="text" name="config[gp_admin_username]" value="<?php echo $config['gp_admin_username']; ?>" id="config[gp_admin_username]"><br/>
		<label for="config[gp_admin_password]"><?php echo __('Admin Password'); ?></label>
		<input type="password" name="config[gp_admin_password]" value="<?php echo $config['gp_admin_password']; ?>" id="config[gp_admin_password]"><br/>
		<label for="config[gp_admin_password2]"><?php echo __('Confirm Password'); ?></label>
		<input type="password" name="config[gp_admin_password2]" value="<?php echo $config['gp_admin_password2']; ?>" id="config[gp_admin_password2]"><br/>
		<label for="config[gp_admin_email]"><?php echo __('Email Address'); ?></label>
		<input type="password" name="config[gp_admin_email]" value="<?php echo $config['gp_admin_email']; ?>" id="config[gp_admin_email]"><br/>
		<small><?php _e('The default username is "admin", with a password of "a".'); ?></small><br/>
	</dd>
</dl>

<?php echo gp_js_focus_on('config[GPDB_NAME]'); ?>

	<p>
		<input type="submit" name="submit" value="<?php echo esc_attr(__('Finish')); ?>" id="submit" />
	</p>
</form>
<?
	endif;
?>
	
<?php gp_tmpl_footer(); ?>