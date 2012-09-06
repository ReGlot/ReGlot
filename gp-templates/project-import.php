<?php
gp_title( $kind == 'originals'?
 	sprintf( __('Import Originals &lt; %s &lt; GlotPress'), esc_html( $project->name ) ) :
	sprintf( __('Import Translations &lt; %s &lt; GlotPress'), esc_html( $project->name ) ) );
gp_breadcrumb_project( $project );
gp_tmpl_header();
?>
<h2><?php echo $kind == 'originals'? __('Import Originals') : __('Import Translations'); ?></h2>
<form action="" method="post" enctype="multipart/form-data">
	<dl>
	<dt><label for="import-file"><?php _e('Import File:'); ?></label></dt>
	<dd><input type="file" name="import-file" id="import-file" /></dd>
	<dt><label for="format"><?php _e('Format:'); ?></label></dt>
	<dd><?php echo gp_select_format('format'); ?></dd>
	<dt><input type="submit" value="<?php echo esc_attr( __('Import') ); ?>"></dt>
</form>
<?php gp_tmpl_footer(); ?>