<?php
gp_title(__('Translations', 'glotpress'));
gp_breadcrumb(array(__('Translations', 'glotpress')));
//wp_enqueue_script('confirm');
gp_tmpl_header();
?>
<h2><?php _e('Translations', 'glotpress') ?></h2>
<?php if ( empty($bundles) ): ?>
<p><?php _e('No translations were found!', 'glotpress'); ?></p>
<?php else: ?>
<p class="actionlist secondary">
    <?php
    switch ( $kind ) {
        case 'slugs':
            gp_link(gp_url_by_translation('slugs'), __('Showing by Slug', 'glotpress'), array('style'=>'text-decoration: none;'));
            echo ' &bull; ';
            gp_link(gp_url_by_translation('locales'), __('Show by Locale', 'glotpress'));
            echo ' &bull; ';
            gp_link(gp_url_by_translation('both'), __('Show by Both', 'glotpress'));
            break;
        case 'locales':
            gp_link(gp_url_by_translation('locales'), __('Showing by Locale', 'glotpress'), array('style'=>'text-decoration: none;'));
            echo ' &bull; ';
            gp_link(gp_url_by_translation('slugs'), __('Show by Slug', 'glotpress'));
            echo ' &bull; ';
            gp_link(gp_url_by_translation('both'), __('Show by Both', 'glotpress'));
            break;
        case 'both':
            gp_link(gp_url_by_translation('both'), __('Showing by Both', 'glotpress'), array('style'=>'text-decoration: none;'));
            echo ' &bull; ';
            gp_link(gp_url_by_translation('locales'), __('Show by Locale', 'glotpress'));
            echo ' &bull; ';
            gp_link(gp_url_by_translation('slugs'), __('Show by Slug', 'glotpress'));
            break;
    }
    if ( GP::$user->logged_in() ) {
    ?>
        <span style="float:right"><?php gp_link(gp_url_user_translations(), __('Show Your Own', 'glotpress') ); ?></span>
    <?php
    }
    ?>
</p>

<ul>
<?php foreach( $bundles as $bundle ): ?>
	<li>
<?php
switch ( $kind ) {
	case 'slugs':
		gp_link("/by-translation/slug/$bundle->slug", esc_html($bundle->name));
		echo " ($bundle->slug)";
		break;
	case 'locales':
		$locale = GP_Locales::by_slug($bundle->locale);
		gp_link("/by-translation/locale/$bundle->locale", esc_html($locale->native_name . ' / ' . $locale->english_name));
		echo " ($locale->slug)";
		break;
	case 'both':
		echo esc_html($bundle->name);
		echo " ($bundle->slug) &rarr; ";
		$locale = GP_Locales::by_slug($bundle->slug);
		echo esc_html($locale->native_name . ' / ' . $locale->english_name);
		echo " ($locale->slug) : ";
		gp_link("/by-translation/both/$bundle->locale/$bundle->slug", __('view', 'glotpress'));
		break;
}
?>
		<?php // gp_link_project_edit($bundle, null, array('class' => 'bubble')); ?>
		<?php // gp_link_project_delete($project, null, array('class' => 'bubble')); ?>
		<?php // if ( $project->active ) echo '<span class="active bubble">Active</span>'; ?>
	</li>
<?php endforeach; ?>
<?php endif; ?>
</ul>
<?php gp_tmpl_footer(); ?>