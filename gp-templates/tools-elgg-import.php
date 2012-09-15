<?php
gp_title(__('Tools'));
gp_tmpl_header();
$project_dropdown1 = gp_projects_dropdown('import[elggcoreproject]', $import['elggcoreproject'], array(), 'Create a new project', true);
$project_dropdown2 = gp_projects_dropdown('import[elgg3rdproject]', $import['elgg3rdproject'], array(), 'Create a new project', true);
?>
<h2><?php _e('GlotPress Tools'); ?></h2>

<form action="" method="post" class="secondary" enctype="multipart/form-data">
<input type="hidden" name="import[gp_handle_settings]" value="on">
<dl>
	<dt><h3><?php _e('Elgg Package Import'); ?></h3></dt>
	<dd>
		<p><?php _e('Import all language data for an Elgg installation. The ZIP file should contain a folder name elgg-&lt;version&gt; and the whole Elgg installation or just the language data below it.
			The mod folders should include the manifest.xml file, used to get data about each plugin. The core plugins are imported as subprojects into an Elgg v&lt;version&gt; project, while all other
			plugins are imported into a top level Third Party Elgg Plugins project. The en locale is used for originals, any other locale is imported as translations.'); ?></p>
		<p>
		<label for="import[elggcoreproject]"><?php _e('Project to import Elgg cores into'); ?></label><br/>
		<?php echo $project_dropdown1; ?>
		</p>
		<p>
		<label for="import[elgg3rdproject]"><?php _e('Project to import third party plugins into'); ?></label><br/>
		<?php echo $project_dropdown2; ?>
		</p>
		<p> Either </p>
		<p>
		<input type="radio" value="zip" name="import[elggtype]" id="elggtypezip">
		<label for="elggfile"><?php _e('Select the Elgg install zip file'); ?></label><br/>
		<input type="file" name="elggfile" id="elggfile"><br/>
		<small><?php _e('You can get this file from <a href="http://www.elgg.org/download.php" target="_blank">the Elgg web site</a>. Make sure the file is in ZIP format.'); ?></small>
		</p>
		<p> Or </p>
		<p>
		<input type="radio" value="dir" name="import[elggtype]" id="elggtypedir">
		<label for="import[elggpath]"><?php _e('Select the Elgg path on the server'); ?></label><br/>
		<input type="text" name="import[elggpath]" id="elggpath" style="width:400px" value="<?php echo $import['elggpath']; ?>"><br/>
		<small><?php _e('If GlotPress and Elgg are running on the same server, then you can specify what the Elgg path is and GlotPress will read from it directly.'); ?></small>
		</p>
	</dd>
</dl>
<p>
	<input type="submit" name="submit" value="<?php echo esc_attr(__('Import')); ?>" id="submit" />
</p>
</form>
<script>
	$('#elggfile').focus(function() {
		$('#elggtypezip').attr('checked', true);
	})
	$('#elggpath').focus(function() {
		$('#elggtypedir').attr('checked', true);
	})
</script>
<?php
gp_tmpl_footer();