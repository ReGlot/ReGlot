<?php
wp_enqueue_style('base');
wp_enqueue_script('common');
?>
<!doctype html>  
<html dir="ltr" ><!-- lang="de-DE" -->
    <head>
        <meta charset="utf-8">
        <title><?php echo gp_title(); ?></title>
        <?php gp_head(); ?>
    </head>
    <body class="no-js">
        <script type="text/javascript">document.body.className = document.body.className.replace('no-js','js');</script>
        <div id="gp-js-message"></div>
        <header>
            <h1>
                <a class="logo" href="<?php echo gp_url('/'); ?>">
                    <img alt="<?php esc_attr(__('GlotPress logo')); ?>" src="<?php echo gp_url_img('glotpress-logo.png'); ?>" />
                </a>
                <?php echo gp_breadcrumb(); ?>
                <nav id="menu">
                    <ul>
                        <?php
                        if (GP::$user && GP::$user->logged_in()) {
                            ?>
                            <li class="first"><?php $user = GP::$user->current();
                            printf(__('Welcome %s! &nbsp; &nbsp; '), $user->display_name);
                            ?></li>
                            <?php
                        }

                        do_action('after_hello');

                        if (gp_get_option('public_home') == 'on') {
                            ?>
                            <li><a href="<?php echo gp_url_project() ?>"><?php _e('Projects'); ?></a></li>
                            <li><a href="<?php echo gp_url_by_translation() ?>"><?php _e('Translations'); ?></a></li>
                            <li><a href="<?php echo gp_url_tools() ?>"><?php _e('Tools'); ?></a></li>
                            <?php
                        }
                        if (GP::$user && GP::$user->logged_in() && GP::$user->admin()) {
                            ?>
                            <li><a href="<?php echo gp_url_settings() ?>"><?php _e('Settings'); ?></a></li>
                            <?php
                        }
                        if (GP::$user && GP::$user->logged_in()) {
                            ?>
                            <li><a href="<?php echo gp_url_users() ?>"><?php _e('Users'); ?></a></li>
                            <li><a href="<?php echo gp_url_user_profile() ?>"><?php _e('Profile'); ?></a></li>
                            <li><a href="<?php echo gp_url_logout() ?>"><?php _e('Log out'); ?></a>
                            <?php
                        } else if (GP::$user) {
                            ?>
                            <?php if (gp_get_option('user_registration') == 'on') { ?>
                            <li><strong><a href="<?php echo gp_url_register(); ?>"><?php _e('Register'); ?></a></strong></li>
                            <?php } ?>
                            <li><strong><a href="<?php echo gp_url_login(); ?>"><?php _e('Log in'); ?></a></strong></li>
                            <?php
                        }
                        ?>
                    </ul>
                </nav>
                <div class="clearfix"></div>
            </h1>
        </header>
        <?php if (gp_notice('error')): ?>
            <div class="error">
                <?php echo gp_notice('error'); //TODO: run kses on notices ?>
            </div>
        <?php endif; ?>
        <?php if (gp_notice()): ?>
            <div class="notice">
                <?php echo gp_notice(); ?>
            </div>
        <?php endif; ?>
        <?php do_action('after_notices'); ?>