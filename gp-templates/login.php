<?php
gp_title(__('Login'));
gp_breadcrumb( array(
	__('Login'),
) );
gp_tmpl_header();
?>
	<h2>Login</h2>
	<?php do_action( 'before_login_form' ); ?>
	<form action="<?php echo gp_url_ssl( gp_url_current() ); ?>" method="post">
	<dl>
		<dt><label for="user_login"><?php _e('Username'); ?></label></dt>
		<dd><input type="text" value="" id="user_login" name="user_login" /></dd>
		
		<dt><label for="user_pass"><?php _e('Password'); ?></label></dt>
		<dd><input type="password" value="" id="user_pass" name="user_pass" /></dd>
	</dl>
	<p>
		<input type="submit" name="submit" value="<?php _e('Login'); ?>" id="submit">
		<?php if ( gp_get_option('user_registration') == 'on' ) { ?>
		or <strong><a href="<?php echo gp_url_register(); ?>"><?php _e('register'); ?></a></strong>
		if you don't have an account yet
		<?php } ?>
	</p>
	<input type="hidden" value="<?php echo esc_attr( gp_get( 'redirect_to' ) ); ?>" id="redirect_to" name="redirect_to" />
</form>
<?php do_action( 'after_login_form' ); ?>
<?php echo gp_js_focus_on('user_login'); ?>
<?php gp_tmpl_footer();
