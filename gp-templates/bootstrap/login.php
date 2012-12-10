<?php
gp_title(__('Login'));
gp_breadcrumb(array(__('Login'),));
gp_tmpl_header();
?>
<div class="container">
    <?php do_action('before_login_form'); ?>
    <form action="<?php echo gp_url_ssl(gp_url_current()); ?>" method="post" class="form-signin form-horizontal">
        <h2 class="form-signin-heading">Login</h2>
        <input type="text" class="input-block-level" id="user_login" name="user_login" placeholder="<?php _e('Username'); ?>">
        <input type="password" class="input-block-level" id="user_pass" name="user_pass" placeholder="<?php _e('Password'); ?>">
        <button type="submit" name="submit" id="submit" class="btn btn-primary"><?php _e('Login'); ?></button>
        <?php if (gp_get_option('user_registration') == 'on') { ?>
        <a href="<?php echo gp_url_register(); ?>" class="btn"><?php _e('register'); ?></a>
        <?php } ?>
        <input type="hidden" value="<?php echo esc_attr(gp_get('redirect_to')); ?>" id="redirect_to" name="redirect_to" />
    </form>
    <?php do_action('after_login_form'); ?>
    <?php echo gp_js_focus_on('user_login'); ?>
</div> <!-- /container -->
<?php gp_tmpl_footer(); ?>
