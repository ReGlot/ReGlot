<?php
wp_enqueue_style( 'base' );
wp_enqueue_script( 'common' );
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
		<title><?php echo gp_title(); ?></title>
		<?php gp_head(); ?>
	</head>
	<body class="no-js">
	<script type="text/javascript">document.body.className = document.body.className.replace('no-js','js');</script>
	    <div id="gp-js-message"></div>
		<h1>
			<a class="logo" href="<?php echo gp_url( '/' ); ?>">
				<img alt="<?php esc_attr(__('GlotPress logo', 'glotpress')); ?>" src="<?php echo gp_url_img( 'glotpress-logo.png' ); ?>" />
			</a>
			<?php echo gp_breadcrumb(); ?>
			<span id="hello">
			<?php
			if ( GP::$user && GP::$user->logged_in() ) {
				$user = GP::$user->current();
				printf( __('Welcome %s! &nbsp; &nbsp; ', 'glotpress'), $user->display_name );
			}

			do_action( 'after_hello' );

			if ( gp_get_option('public_home') == 'on' ) {
			?>
				<a href="<?php echo gp_url_project() ?>"><?php _e('Projects', 'glotpress'); ?></a> &bull;
				<a href="<?php echo gp_url_by_translation() ?>"><?php _e('Translations', 'glotpress'); ?></a> &bull;
				<a href="<?php echo gp_url_tools() ?>"><?php _e('Tools', 'glotpress'); ?></a> &bull;
			<?php
			}
			if ( GP::$user && GP::$user->logged_in() && GP::$user->admin() ) {
			?>
				<a href="<?php echo gp_url_settings() ?>"><?php _e('Settings', 'glotpress'); ?></a> &bull;
			<?php
			}
			if ( GP::$user && GP::$user->logged_in() ) {
			?>
				<a href="<?php echo gp_url_users() ?>"><?php _e('Users', 'glotpress'); ?></a> &bull;
				<a href="<?php echo gp_url_user_profile() ?>"><?php _e('Profile', 'glotpress'); ?></a> &bull;
				<a href="<?php echo gp_url_logout() ?>"><?php _e('Log out', 'glotpress'); ?></a>
			<?php
			} else if ( GP::$user ) {
			?>
				<?php if ( gp_get_option('user_registration') == 'on' ) { ?>
				<strong><a href="<?php echo gp_url_register(); ?>"><?php _e('Register', 'glotpress'); ?></a></strong> &bull;
				<?php } ?>
				<strong><a href="<?php echo gp_url_login(); ?>"><?php _e('Log in', 'glotpress'); ?></a></strong>
			<?php
			}
			?>
			</span>
			<div class="clearfix"></div>
		</h1>
		<div class="clear after-h1"></div>
		<?php if (gp_notice('error')): ?>
			<div class="error">
				<?php echo gp_notice( 'error' ); //TODO: run kses on notices ?>
			</div>
		<?php endif; ?>
		<?php if (gp_notice()): ?>
			<div class="notice">
				<?php echo gp_notice(); ?>
			</div>
		<?php endif; ?>
		<?php do_action( 'after_notices' ); ?>