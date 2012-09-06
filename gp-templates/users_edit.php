<?php
gp_title( $user ? __('Edit User') : __('Create User') );

gp_tmpl_header();
?>

<h2><?php echo wptexturize($user ? sprintf(__('User #%d'), $user->id) : __('New User')); ?></h2>
<form action="" method="post">
<input type="hidden" name="user[gp_handle_settings]" value="on">
<input type="hidden" name="user[gp_user_id]" value="<?php echo $user ? $user->id : '' ?>">
<dl>
	<dt><h3><?php echo __('Basic Information'); ?></h3></dt>
	<dd>
		<label for="user[user_login]"><?php echo __('Login Username'); ?></label>
		<input type="text" name="user[user_login]" value="<?php echo $user->user_login; ?>" id="user[user_login]"><br/>
		<label for="user[display_name]"><?php echo __('Display Name'); ?></label>
		<input type="text" name="user[display_name]" value="<?php echo $user->display_name; ?>" id="user[display_name]"><br/>
		<small><?php _e('The name that will be used for display on the website') ?></small><br/>
		<label for="user[user_pass]"><?php echo __('Password'); ?></label>
		<input type="password" name="user[user_pass]" value="" id="user[user_pass]"><br/>
		<label for="user[user_pass2]"><?php echo __('Confirm Password'); ?></label>
		<input type="password" name="user[user_pass2]" value="" id="user[user_pass2]"><br/>
<?php if ( $user ) { ?>
		<small><?php _e('Leave both password fields blank to leave password unchanged') ?></small><br/>
<?php } ?>
		<label for="user[user_email]"><?php echo __('Email Address'); ?></label>
		<input type="text" name="user[user_email]" value="<?php echo $user->user_email; ?>" id="user[user_email]"><br/>
		<label for="user[user_url]"><?php echo __('Website URL (if any)'); ?></label>
		<input type="text" name="user[user_url]" value="<?php echo $user->user_url; ?>" id="user[user_url]"><br/>
	</dd>
</dl>

<?php echo gp_js_focus_on('user[user_login]'); ?>

	<p>
		<input type="submit" name="submit" value="<?php echo esc_attr(__('Save')); ?>" id="submit" />
		or <strong><a href="<?php echo gp_url('/admin/users'); ?>"><?php _e('go back'); ?></a></strong> to the user list
	</p>
</form>
	
<?php gp_tmpl_footer(); ?>