<?php
gp_title(__('Welcome'));
gp_tmpl_header();
?>
	<h2>Welcome to the Elgg Translation Portal</h2>
	<p>
		In these pages you can find a collection of language packs for <a href="http://www.elgg.org">Elgg</a>,
		one - if not the - greatest engine for social networking.
	</p>
	<p>
		The best way to use this portal is together with the <a href="http://community.elgg.org/plugins/1095926/1.0/language-packs">Language Pack plugin</a>
		for Elgg, which imports and exports Zip files in exactly the format produced or processed
		by this translation portal.
	</p>
	<p>
		You can browse translations
		<ul>
			<li><a href="<?php echo gp_url_project(); ?>">by project</a></li>
			<li><a href="<?php echo gp_url_by_translation(); ?>">by language or translation sets</a></li>
            <?php if ( GP::$user->logged_in() ) { ?>
            <li>by user (the translation column in the <a href="<?php echo gp_url_users(); ?>">users list page</a>)</li>
            <?php } ?>
		</ul>
	</p>
    <?php if ( GP::$user->logged_in() ) { ?>
    <p>
        As a logged in user, you can suggest or modify existing translation entries from any of the above links.<br>
        You can also <a href="<?php echo gp_url_user_profile(); ?>">edit your profile</a> or view and work
        on the <a href="<?php echo gp_url_user_translations(); ?>">translation entries contributed by yourself</a>.
    </p>
    <?php } else { ?>
    <p>
        If instead you want to actively help translating <a href="http://www.elgg.org">Elgg</a> into your language,
        please <a href="<?php echo gp_url_login(); ?>">log in</a> to <?php echo gp_app_name(); ?>
        or <a href="<?php echo gp_url_register(); ?>">register</a> if you don't have an account yet.
    </p>
    <?php } ?>
	<h3>Notice</h3>
	<p>
		This project is maintained by <a href="http://www.federicomestrone.com">myself</a> for the Elgg community, but is not directly affiliated to Elgg.<br>
		This project is built on <?php echo gp_app_name(); ?>, an extension and hack of GlotPress also maintained by <a href="http://www.federicomestrone.com">myself</a>.
	</p>
<?php
gp_tmpl_footer();
