<?php
gp_title(__('Tools'));
gp_tmpl_header();
?>
<h2><?php _e('GlotPress Tools'); ?></h2>

<form action="" method="post" class="secondary" enctype="multipart/form-data">
<input type="hidden" name="import[gp_handle_settings]" value="on">
<dl>
	<dt><h3><?php _e('Elgg Package Import'); ?></h3></dt>
	<dd>
		<label for="elggfile"><?php _e('Select the Elgg install zip file'); ?></label><br/>
		<input type="file" name="elggfile" id="elggfile"><br/>
		<small><?php _e('You can get this file from <a href="http://www.elgg.org/download.php" target="_blank">the Elgg web site</a>. Make sure the file is in ZIP format.'); ?></small>
	</dd>
</dl>
<p>
	<input type="submit" name="submit" value="<?php echo esc_attr(__('Import')); ?>" id="submit" />
</p>
</form>
<?php
gp_tmpl_footer();