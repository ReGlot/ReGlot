<?php
gp_title(__('Login'));
gp_breadcrumb(array(__('Login'),));
gp_tmpl_header();
?>
<section id="content">
    <h2>Login</h2>
    <article>
        <?php do_action('before_login_form'); ?>
        <form action="<?php echo gp_url_ssl(gp_url_current()); ?>" method="post" class="form-horizontal">
            <div class="control-group">
                <label class="control-label" for="user_login"><?php _e('Username'); ?></label>
                <div class="controls">
                    <input type="text" id="user_login" name="user_login" placeholder="<?php _e('Username'); ?>">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="user_pass"><?php _e('Password'); ?></label>
                <div class="controls">
                    <input type="password" id="user_pass" name="user_pass" placeholder="<?php _e('Password'); ?>">
                </div>
            </div>
            <div class="control-group">
                <div class="controls">
                    <button type="submit" name="submit" id="submit" class="btn btn-primary"><?php _e('Login'); ?></button>
                    <?php if (gp_get_option('user_registration') == 'on') { ?>
                    or <a href="<?php echo gp_url_register(); ?>" class="btn"><?php _e('register'); ?></a>
                    <?php } ?>
                </div>
            </div>
            <input type="hidden" value="<?php echo esc_attr(gp_get('redirect_to')); ?>" id="redirect_to" name="redirect_to" />
        </form>
        <?php do_action('after_login_form'); ?>
        <?php echo gp_js_focus_on('user_login'); ?>
    </article>
</section>
<?php gp_tmpl_footer(); ?>
