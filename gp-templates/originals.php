<?php
gp_title(__('Originals', 'glotpress'));
gp_breadcrumb( array(
	gp_project_links_from_root( $project ),
	gp_link_get($url . '/originals', __('Originals', 'glotpress')),
) );
$parity = gp_parity_factory();

gp_tmpl_header();

gp_tmpl_load('project-import', get_defined_vars());

$i = 0;
?>
<h2>
	Original Strings of <?php echo esc_html( $project->name ); ?>
</h2>
<?php echo gp_pagination( $page, $per_page, $total_originals_count ); ?>
<table id="translations" class="translations clear originals">
	<thead>
	<tr>
		<th class="original"><?php _e('Context', 'glotpress'); ?></th>
		<th class="translation"><?php _e('Original Text', 'glotpress'); ?></th>
		<th class="comment"><?php _e('Comment', 'glotpress'); ?></th>
	</tr>
	</thead>
<?php foreach( $originals as $o ):
		gp_tmpl_load( 'original-row', get_defined_vars() );
?>
<?php endforeach; ?>
<?php
	if ( !$originals ):
?>
	<tr><td colspan="3"><?php _e('No originals were found!', 'glotpress'); ?></td></tr>
<?php
	endif;
?>
</table>
<?php echo gp_pagination( $page, $per_page, $total_originals_count ); ?>
<div style="clear: both"></div>
<?php
gp_tmpl_footer();
