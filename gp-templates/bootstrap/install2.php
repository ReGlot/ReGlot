<?php
gp_title(__('Install'));
gp_breadcrumb(array(
    'install' == $action ? __('Install') : __('Upgrade'),
));

$config_defaults = array(
    'gp_admin_username' => 'admin',
    'gp_admin_password' => 'a',
    'gp_admin_password2' => 'a'
);

$config = array_merge($config_defaults, $config);

gp_tmpl_header();
?>
<section id="content">
    <h2><?php echo wptexturize(sprintf(__('Installation Process (phase %d of %d)'), 3, GP_TOT_INSTALL_PAGES)); ?></h2>
    <dl>
        <dt><h3><?php echo __('Product installed successfully'); ?></h3></dt>
        <dd>
            <span><?php echo __('You can access your ' . gp_app_name() . ' installation <a href="' . gp_url_base() . '">from here</a>'); ?></span>
            <br/>
            <small><?php _e('Or you can <a href="' . gp_url_login() . '">log in</a> to the product straightaway with the username and password you have just created'); ?></small>
        </dd>
    </dl>
</section>	
<?php gp_tmpl_footer(); ?>