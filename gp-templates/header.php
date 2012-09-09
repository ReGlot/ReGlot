<?php
wp_enqueue_style( 'base' );
wp_enqueue_script( 'jquery' );
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
				<img alt="<?php esc_attr(__('GlotPress logo')); ?>" src="<?php echo gp_url_img( 'glotpress-logo.png' ); ?>" />
			</a>
			<?php echo gp_breadcrumb(); ?>
			<span id="hello">
			<?php
			if ( GP::$user && GP::$user->logged_in() ):
				$user = GP::$user->current();
				
				printf( __('Welcome %s!'), $user->display_name );
				?>
				 &nbsp; &nbsp;
				<a href="<?php echo gp_url('/projects')?>"><?php _e('Projects'); ?></a> &bull;
				<a href="<?php echo gp_url('/by-translation/locales')?>"><?php _e('Translations'); ?></a> &bull;
				<a href="<?php echo gp_url('/admin/users/edit/' . GP::$user->current()->id)?>"><?php _e('Profile'); ?></a> &bull;
				<?php
				if ( GP::$user->admin() ):
				?>
					<a href="<?php echo gp_url('/tools')?>"><?php _e('Tools'); ?></a> &bull;
					<a href="<?php echo gp_url('/admin/settings')?>"><?php _e('Settings'); ?></a> &bull;
					<a href="<?php echo gp_url('/admin/users')?>"><?php _e('Users'); ?></a> &bull;
				<?
				endif;
				?>
				<a href="<?php echo gp_url('/logout')?>"><?php _e('Log out'); ?></a>
			<?php elseif ( GP::$user ): ?>
				<?php if ( gp_get_option('user_registration') == 'on' ) { ?>
				<strong><a href="<?php echo gp_url('/admin/users/register'); ?>"><?php _e('Register'); ?></a></strong> &bull;
				<?php } ?>
				<strong><a href="<?php echo gp_url_login(); ?>"><?php _e('Log in'); ?></a></strong>
			<?php endif; ?>
			<?php do_action( 'after_hello' ); ?>
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