<?php
gp_title(__('Tools'));
gp_tmpl_header();
$project_dropdown1 = gp_projects_dropdown('export[elggcoreproject]', $export['elggcoreproject'], array(), 'Select an Elgg project', true);
$project_dropdown2 = gp_projects_dropdown('export[elgg3rdproject]', $export['elgg3rdproject'], array(), 'No 3rd Party project', true);
$version_dropdown = gp_select('export[version]', array('' => '&mdash; Choose a version &mdash;', '2' => 'Version 1.9 and later', '1' => 'Version 1.8 and earlier'), $export['version']);
?>
<h2><?php _e('GlotPress Tools'); ?></h2>

<form action="" method="post" class="secondary" enctype="multipart/form-data">
<input type="hidden" name="export[gp_handle_settings]" value="on">
<dl>
	<dt><h3><?php _e('Elgg Package Export'); ?></h3></dt>
	<dd>
		<p><?php _e('Import all language data for an Elgg installation. The ZIP file should contain a folder name elgg-&lt;version&gt; and the whole Elgg installation or just the language data below it.
			The mod folders should include the manifest.xml file, used to get data about each plugin. The core plugins are imported as subprojects into an Elgg v&lt;version&gt; project, while all other
			plugins are imported into a top level Third Party Elgg Plugins project. The en locale is used for originals, any other locale is imported as translations.'); ?></p>
		<p>
		<label for="export[elggcoreproject]"><?php _e('Project to export Elgg cores from'); ?></label><br/>
		<?php echo $project_dropdown1; ?>
		</p>
		<p>
		<label for="export[elgg3rdproject]"><?php _e('Project to optionally export third party plugins into'); ?></label><br/>
		<?php echo $project_dropdown2; ?>
		</p>
		<p>
		<label for="export[elggpath]"><?php _e('Type the name for the file to download'); ?></label><br/>
		<input type="text" name="export[elggpath]" id="elggpath" style="width:400px" value="<?php echo $export['elggpath']; ?>"><br/>
		<small><?php _e('You can leave this blank and the file will be called <i>elgg-languages.zip</i>.'); ?></small>
		</p>
		<p>
		<label for="export[version]"><?php _e('The version of Elgg to export for'); ?></label><br/>
		<?php echo $version_dropdown; ?>
		</p>
	</dd>
</dl>
<p>
	<input type="submit" name="submit" value="<?php echo esc_attr(__('Export')); ?>" id="submit" />
</p>
</form>
<?php
gp_tmpl_footer();