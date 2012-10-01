<?php
gp_title(esc_html($project->name));
gp_breadcrumb_project( $project );
wp_enqueue_script( 'common' );
wp_enqueue_script('confirm');
$edit_link = gp_link_project_edit_get( $project, '(edit)' );
$delete_link = gp_link_project_delete_get($project, '(del)');
$parity = gp_parity_factory();
gp_tmpl_header();
?>
<h2><?php echo esc_html( $project->name ); ?> <?php echo $edit_link; ?> <?php echo $delete_link; ?></h2>
<p class="description">
	<?php echo $project->description; ?>
</p>

<?php if ( $can_write ): ?>

<div class="actionlist">
	<a href="#" class="project-actions" id="project-actions-toggle"><?php _e('Project actions &darr;', 'glotpress'); ?></a>
	<div class="project-actions hide-if-js">
		<ul>
			<li><?php gp_link( gp_url_project( $project, '-originals' ), __( 'View/Import Originals' , 'glotpress') ); ?></li>
			<li><?php gp_link( gp_url_project( $project, array( '-permissions' ) ), __('Permissions', 'glotpress') ); ?></li>
			<li><?php gp_link( gp_url_project( '', '-new', array('parent_project_id' => $project->id) ), __('New Sub-Project', 'glotpress') ); ?></li>
			<li><?php gp_link( gp_url( '/sets/-new', array( 'project_id' => $project->id ) ), __('New Translation Set', 'glotpress') ); ?></li>
			<li><?php gp_link( gp_url_project( $project, array( '-mass-create-sets' ) ), __('Mass-create Translation Sets', 'glotpress') ); ?></li>
			<?php if ( $translation_sets ): ?>
			<li>
				<a href="#" class="personal-options" id="personal-options-toggle"><?php _e('Personal project options &darr;', 'glotpress'); ?></a>
				<div class="personal-options">
					<form action="<?php echo gp_url_project( $project, '-personal' ); ?>" method="post">
					<dl>
						<dt><label for="source-url-template"><?php _e('Source file URL', 'glotpress');  ?></label></dt>
						<dd>
							<input type="text" value="<?php echo esc_html( $project->source_url_template() ); ?>" name="source-url-template" id="source-url-template" />
							<small><?php _e('URL to a source file in the project. You can use <code>%file%</code> and <code>%line%</code>. Ex. <code>http://trac.example.org/browser/%file%#L%line%</code>', 'glotpress'); ?></small>
						</dd>
					</dl>
					<p>
						<input type="submit" name="submit" value="<?php echo esc_attr(__('Save &rarr;', 'glotpress')); ?>" id="save" />
						<a class="ternary" href="#" onclick="jQuery('#personal-options-toggle').click();return false;"><?php _e('Cancel', 'glotpress'); ?></a>
					</p>		
					</form>
				</div>
			</li>
		<?php endif; ?>
		</ul>
	</div>
</div>
<?php endif; ?>


<?php if ($sub_projects): ?>
<div id="sub-projects"  style="width:<?php echo $translation_sets ? 20 : 100; ?>%;">
<h3><?php _e('Sub-projects', 'glotpress'); ?></h3>
<dl>
<?php foreach($sub_projects as $sub_project): ?>
	<dt>
		<?php gp_link_project( $sub_project, esc_html( $sub_project->name ) ); ?>
		<?php gp_link_project_edit( $sub_project, null, array( 'class' => 'bubble' ) ); ?>
		<?php gp_link_project_delete($sub_project, null, array('class' => 'bubble')); ?>
		<?php if ( $sub_project->active ) echo "<span class='active bubble'>Active</span>"; ?>
	</dt>
	<dd>
		<?php 
		if ( $translation_sets ) {
			echo esc_html(gp_html_excerpt($sub_project->description, 120));
		} else {
			echo esc_html($sub_project->description);
		}
		?>
	</dd>
<?php endforeach; ?>
</dl>
</div>
<?php endif; ?>

<?php gp_tmpl_load('translation-sets', get_defined_vars()); ?>

<div class="clear"></div>


<script type="text/javascript" charset="utf-8">
	$gp.showhide('a.personal-options', 'div.personal-options', {
		show_text: 'Personal project options &darr;',
		hide_text: 'Personal project options &uarr;',
		focus: '#source-url-template',
		group: 'personal'
	});
	$('div.personal-options').hide();
	$gp.showhide('a.project-actions', 'div.project-actions', {
		show_text: 'Project actions &darr;',
		hide_text: 'Project actions &uarr;',
		focus: '#source-url-template',
		group: 'project'
	});
</script>
<?php gp_tmpl_footer();
