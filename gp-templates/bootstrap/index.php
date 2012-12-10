<?php
gp_title(__('Welcome'));
gp_tmpl_header();
?>
<div class="hero-unit">
    <h1>Welcome</h1>
    <p>
        You can browse translations
    <ul>
        <li><a href="<?php echo gp_url_project(); ?>">by project</a></li>
        <li><a href="<?php echo gp_url_by_translation(); ?>">by language or translation set</a></li>
        <?php if (GP::$user->logged_in()) { ?>
            <li>by user (the translation column in the <a href="<?php echo gp_url_users(); ?>">users list page</a>)</li>
        <?php } ?>
    </ul>
</p>
<?php if (GP::$user->logged_in()) { ?>
    <p>
        As a logged in user, you can suggest or modify existing translations from any of the above links.<br>
        You can also <a href="<?php echo gp_url_user_profile(); ?>">edit your profile</a> or view and work
        on the <a href="<?php echo gp_url_user_translations(); ?>">translation entries contributed by yourself</a>.
    </p>
<?php } else { ?>
    <p>
        If you want to help translating instead,
        please <a href="<?php echo gp_url_login(); ?>">log in</a> to <?php echo gp_app_name() ?>
        or <a href="<?php echo gp_url_register(); ?>">register</a> if you don't have an account yet.
    </p>
<?php } ?>
</div>
<?php gp_tmpl_footer(); ?>
