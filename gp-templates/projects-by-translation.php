<?php
// title and breadcrumb are set later
$descTitle = 'A list of all projects with translation sets ';
switch ( $kind ) {
	case 'locale':
		$locale = GP_Locales::by_slug($locale_slug);
		$title = $locale->english_name;
		$headerTitle = "$locale->native_name / $locale->english_name";
		$descTitle .= "in $locale->english_name";
		$url = 'by-translation/locales';
		$suffix = 'l';
		break;
	case 'slug':
		$title = "Slug $slug";
		$headerTitle = sprintf(__('By Slug \'%s\''), $slug);
		$descTitle .= "with slug '$slug'";
		$url = 'by-translation/slugs';
		$suffix = 's';
		break;
	case 'both':
		$locale = GP_Locales::by_slug($locale_slug);
		$title = "$locale->english_name + $slug";
		$headerTitle = "$locale->native_name / $locale->english_name " . sprintf(__('and slug \'%s\''), $slug);
		$descTitle .= "in $locale->english_name with slug '$slug'";
		$url = 'by-translation/both';
		$suffix = 'b';
		break;
}
gp_title(sprintf(__('%s &lt; GlotPress'), esc_html($title)));
gp_breadcrumb(array(
	gp_link_get(gp_url($url),__('Translations')),
	$title
	));
wp_enqueue_script('common');
wp_enqueue_script('confirm');
$parity = gp_parity_factory();
gp_tmpl_header();
?>
<h2><?php echo esc_html($headerTitle); ?></h2>
<p class="description">
	<?php echo $descTitle; ?>
</p>

<?php gp_tmpl_load('translation-sets', get_defined_vars()); ?>

<div class="clear"></div>
<?php gp_tmpl_footer();
