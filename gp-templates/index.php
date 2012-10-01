<?php
gp_title(__('Welcome', 'glotpress'));
gp_tmpl_header();
?>
<h2><?php _e('Welcome', 'glotpress'); ?></h2>
<p>
    <?php printf(__('Welcome to %s, based on GlotPress.', 'glotpress'), gp_app_name() ); ?>
</p>
<p>
    <?php _e('Translations may be browsed by:', 'glotpress'); ?>
    <ul>
        <li><a href="<?php echo gp_url_project(); ?>"><?php _e('project', 'glotpress'); ?></a></li>
        <li><a href="<?php echo gp_url_by_translation(); ?>"><?php _e('language or translation set', 'glotpress'); ?></a></li>
<?php if ( GP::$user->logged_in() ) { ?>
        <li><?php printf( __('user (the translation column in the <a href="%s">users list page</a>)', 'glotpress'), gp_url_users() ); ?></li>
<?php } ?>
    </ul>
</p>
<?php if ( GP::$user->logged_in() ) { ?>
<p>
    <?php printf(__('As a logged in user, you may suggest or modify existing translations from any of the above links.<br/>You may also <a href="%s">edit your profile</a> or view and work on the <a href="%s">translation entries contributed by yourself</a>.', 'glotpress'), gp_url_user_profile(), gp_url_user_translations() ); ?>
</p>
<?php } else { ?>
<p>
    <?php printf(__('If you want to help translating instead, please <a href="%s">log in</a> to %s or <a href="%s">register</a> if you don\'t have an account yet.', 'glotpress'), gp_url_login(), gp_app_name(), gp_url_register() ); ?>
</p>
<?php } ?>
<?php
gp_tmpl_footer();
