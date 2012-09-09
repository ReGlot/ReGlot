<?php
gp_title(__('Translations &lt; GlotPress'));
gp_breadcrumb(array(__('Translations')));
//wp_enqueue_script('confirm');
gp_tmpl_header();
?>
<h2><?php _e('Translations') ?></h2>
<p class="actionlist secondary">
<?php	
switch ( $kind ) {
	case 'slugs':
		gp_link(gp_url('/by-translation/slugs'), __('Showing by Slug'), array('style'=>'text-decoration: none;'));
		echo ' &bull; ';
		gp_link(gp_url('/by-translation/locales'), __('Show by Locale'));
		echo ' &bull; ';
		gp_link(gp_url('/by-translation/both'), __('Show by Both'));
		break;
	case 'locales':
		gp_link(gp_url('/by-translation/locales'), __('Showing by Locale'), array('style'=>'text-decoration: none;'));
		echo ' &bull; ';
		gp_link(gp_url('/by-translation/slugs'), __('Show by Slug'));
		echo ' &bull; ';
		gp_link(gp_url('/by-translation/both'), __('Show by Both'));
		break;
	case 'both':
		gp_link(gp_url('/by-translation/both'), __('Showing by Both'), array('style'=>'text-decoration: none;'));
		echo ' &bull; ';
		gp_link(gp_url('/by-translation/locales'), __('Show by Locale'));
		echo ' &bull; ';
		gp_link(gp_url('/by-translation/slugs'), __('Show by Slug'));
		break;
}
?>
</p>
<ul>
<?php foreach( $bundles as $bundle ): ?>
	<li>
<?php
switch ( $kind ) {
	case 'slugs':
		gp_link_project($bundle, esc_html($bundle->name));
		echo " ($bundle->slug)";
		break;
	case 'locales':
		$locale = GP_Locales::by_slug($bundle->locale);
		gp_link_project($bundle, esc_html($locale->native_name . ' / ' . $locale->english_name));
		echo " ($locale->slug)";
		break;
	case 'both':
		gp_link_project($bundle, esc_html($bundle->name));
		echo " ($bundle->slug) &rarr; ";
		$locale = GP_Locales::by_slug($bundle->slug);
		gp_link_project($bundle, esc_html($locale->native_name . ' / ' . $locale->english_name));
		echo " ($locale->slug)";
		break;
}
?>
		<?php gp_link_project_edit($bundle, null, array('class' => 'bubble')); ?>
		<?php gp_link_project_delete($project, null, array('class' => 'bubble')); ?>
		<?php if ( $project->active ) echo '<span class="active bubble">Active</span>'; ?>
	</li>
<?php endforeach; ?>
</ul>
<?php gp_tmpl_footer(); ?>