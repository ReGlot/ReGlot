<?php
gp_title(__('Tools'));
gp_tmpl_header();
?>
<h2><?php _e('GlotPress Tools'); ?></h2>
<h3><?php _e('Import/Export'); ?></h3>

<ul>
	<li>
		<a href="<?php echo gp_url('/tools/elgg_import'); ?>"><?php _e('Elgg Import'); ?></a>: <?php _e('Create a set of GlotPress projects from an Elgg installation package'); ?>
	</li>
</ul>

<?php
gp_tmpl_footer();