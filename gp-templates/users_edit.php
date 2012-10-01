<?php
$exists = $user && $user->id;
gp_title($exists ? __('Edit User', 'glotpress') : __('Create User', 'glotpress'));
gp_tmpl_header();
?>

<h2><?php echo wptexturize($exists ? sprintf(__('User #%d', 'glotpress'), $user->id) : __('New User', 'glotpress')); ?></h2>
<form action="" method="post">
<input type="hidden" name="user[gp_handle_settings]" value="on">
<input type="hidden" name="user[gp_user_id]" value="<?php echo $exists ? $user->id : '' ?>">
<dl>
	<dt><h3><?php echo __('Basic Information', 'glotpress'); ?></h3></dt>
	<dd>
		<label for="user[user_login]"><?php echo __('Login Username', 'glotpress'); ?></label>
		<input type="text" name="user[user_login]" value="<?php echo $user->user_login; ?>" id="user[user_login]"><br/>
		<label for="user[display_name]"><?php echo __('Display Name', 'glotpress'); ?></label>
		<input type="text" name="user[display_name]" value="<?php echo $user->display_name; ?>" id="user[display_name]"><br/>
		<small><?php _e('The name that will be used for display on the website', 'glotpress') ?></small><br/>
		<label for="user[user_pass]"><?php echo __('Password', 'glotpress'); ?></label>
		<input type="password" name="user[user_pass]" value="" id="user[user_pass]"><br/>
		<label for="user[user_pass2]"><?php echo __('Confirm Password', 'glotpress'); ?></label>
		<input type="password" name="user[user_pass2]" value="" id="user[user_pass2]"><br/>
<?php if ( $exists ) { ?>
		<small><?php _e('Leave both password fields blank to leave password unchanged', 'glotpress') ?></small><br/>
<?php } ?>
		<label for="user[user_email]"><?php echo __('Email Address', 'glotpress'); ?></label>
		<input type="text" name="user[user_email]" value="<?php echo $user->user_email; ?>" id="user[user_email]"><br/>
		<label for="user[user_url]"><?php echo __('Website URL (if any)', 'glotpress'); ?></label>
		<input type="text" name="user[user_url]" value="<?php echo $user->user_url; ?>" id="user[user_url]"><br/>
	</dd>
</dl>

<?php echo gp_js_focus_on('user[user_login]'); ?>

	<p>
		<input type="submit" name="submit" value="<?php echo esc_attr(__('Save', 'glotpress')); ?>" id="submit" />
<?php if ( !$register ) { ?>
		or <strong><a href="<?php echo gp_url('/admin/users'); ?>"><?php _e('go back', 'glotpress'); ?></a></strong> to the user list
<?php } ?>
	</p>
</form>

<?php gp_tmpl_footer(); ?>