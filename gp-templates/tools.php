<?php
gp_title(__('Tools'));
gp_tmpl_header();
?>
<h2><?php _e('GlotPress Tools'); ?></h2>
<h3><?php _e('Import/Export'); ?></h3>

<ul>
	<?php if ( GP::$user->current()->admin() ) { ?>
	<li>
		<a href="<?php echo gp_url('/tools/elgg-import'); ?>"><?php _e('Elgg Import'); ?></a>: <?php _e('Create a set of GlotPress projects from an Elgg language pack'); ?>
	</li>
	<?php } ?>
	<li>
		<a href="<?php echo gp_url('/tools/elgg-export'); ?>"><?php _e('Elgg Export'); ?></a>: <?php _e('Create a language pack from a set of GlotPress projects for Elgg'); ?>
	</li>
</ul>

<?php
gp_tmpl_footer();