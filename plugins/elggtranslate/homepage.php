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
		</ul>
	</p>
	<p>
		If instead you want to actively help translating <a href="<?php echo gp_url_project(); ?>">Elgg</a> into your language,
		please <a href="<?php echo gp_url_login(); ?>">log in</a> to ReGlot
		or <a href="<?php echo gp_url_register(); ?>">register</a> if you don't have an account yet.
	</p>
	<h3>Notice</h3>
	<p>
		This project is maintained by myself for the Elgg community, but is not directly affiliated to Elgg.<br>
		This project is built on ReGlot, an extension of GlotPress also maintained by myself.
	</p>
<?php
gp_tmpl_footer();
