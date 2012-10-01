<?php
wp_enqueue_script('elgg_import_export');
gp_title(__('Elgg Export'));
gp_tmpl_header();
$project_dropdown1 = gp_projects_dropdown('export[elggcoreproject]', $export['elggcoreproject'], array(), 'Select an Elgg project', true);
$project_dropdown2 = gp_projects_dropdown('export[elgg3rdproject]', $export['elgg3rdproject'], array(), 'No 3rd Party project', true);
$version_dropdown = gp_select('export[version]', array('' => '&mdash; Choose a version &mdash;', '2' => 'Version 1.9 and later', '1' => 'Version 1.8 and earlier'), $export['version']);
?>
<h2><?php _e('GlotPress Tools'); ?></h2>
<?php if ( GP::$project->by_slug('elgg') ): ?>
<form action="" method="post" class="filters-toolbar" enctype="multipart/form-data">
<input type="hidden" name="export[gp_handle_settings]" value="on">
<input type="hidden" name="export[cores_selection]" value="">
<input type="hidden" name="export[plugins_selection]" value="">
<input type="hidden" name="export[locales_selection]" value="">
<dl>
	<dt><h3><?php _e('Elgg Package Export'); ?></h3></dt>
	<dd>
		<p><?php _e('Import all language data for an Elgg installation. The ZIP file should contain a folder name elgg-&lt;version&gt; and the whole Elgg installation or just the language data below it.
			The mod folders should include the manifest.xml file, used to get data about each plugin. The core plugins are imported as subprojects into an Elgg v&lt;version&gt; project, while all other
			plugins are imported into a top level Third Party Elgg Plugins project. The en locale is used for originals, any other locale is imported as translations.'); ?></p>
	<div>
	<a href="#" class="revealing cores"><?php _e('Core &amp; Bundled &darr;'); ?></a> <span class="separator">&bull;</span>
	<a href="#" class="revealing plugins"><?php _e('Extra Plugins &darr;'); ?></a> <span class="separator">&bull;</span>
	<a href="#" class="revealing locales"><?php _e('Locales &darr;'); ?></a> <strong class="separator"></strong>
	</div>

	<div class="cores hidden clearfix">
			<ul class="filters-expanded selection-expanded">
				<li>Available for Export<br/><select id="select1_cores" multiple="multiple" class="elgg-input-dropdown languagepacks-select">
				</select>
				</li>
				<li><div class="languagepacks-buttons">
<p><input type="button" id="add_cores" value="Add Selected" class="elgg-button">
</p><p><input type="button" id="add-all_cores" value="Add All" class="elgg-button">
</p><p><input type="button" id="remove-all_cores" value="Remove All" class="elgg-button">
</p><p><input type="button" id="remove_cores" value="Remove Selected" class="elgg-button">
</p></div></li><li>Selected for Export<br/><select id="select2_cores" multiple="multiple" class="elgg-input-dropdown languagepacks-select">
		<?php
		$projects = GP::$project->by_slug('elgg')->sub_projects();
		foreach ( $projects as $project ) {
			echo "<option value=\"$project->id\">$project->name</option>\n";
		}
		?>
</select>
</li></ul>		
	</div>

	<div class="plugins hidden clearfix">
			<ul class="filters-expanded selection-expanded">
				<li>Available for Export<br/><select id="select1_plugins" multiple="multiple" class="elgg-input-dropdown languagepacks-select">
				</select>
				</li>
				<li><div class="languagepacks-buttons">
<p><input type="button" id="add_plugins" value="Add Selected" class="elgg-button">
</p><p><input type="button" id="add-all_plugins" value="Add All" class="elgg-button">
</p><p><input type="button" id="remove-all_plugins" value="Remove All" class="elgg-button">
</p><p><input type="button" id="remove_plugins" value="Remove Selected" class="elgg-button">
</p></div></li><li>Selected for Export<br/><select id="select2_plugins" multiple="multiple" class="elgg-input-dropdown languagepacks-select">
		<?php
		$projects = GP::$project->by_path('elgg3rd') ? GP::$project->by_path('elgg3rd')->sub_projects() : array();
		foreach ( $projects as $project ) {
			echo "<option value=\"$project->id\">$project->name</option>\n";
		}
		?>
</select>
</li></ul>		
	</div>

	<div class="locales hidden clearfix">
			<ul class="filters-expanded selection-expanded">
				<li>Available for Export<br/><select id="select1_locales" multiple="multiple" class="elgg-input-dropdown languagepacks-select">
				</select>
		<br/>
		<input type="checkbox" name="export[empty]" value="on">
		<label for="export[empty]"><?php _e('Create empty language files'); ?></label><br/>
				</li>
				<li><div class="languagepacks-buttons">
<p><input type="button" id="add_locales" value="Add Selected" class="elgg-button">
</p><p><input type="button" id="add-all_locales" value="Add All" class="elgg-button">
</p><p><input type="button" id="remove-all_locales" value="Remove All" class="elgg-button">
</p><p><input type="button" id="remove_locales" value="Remove Selected" class="elgg-button">
</p></div></li><li>Selected for Export<br/><select id="select2_locales" multiple="multiple" class="elgg-input-dropdown languagepacks-select">
		<?php
		$locales = GP::$translation_set->locales_in_use();
		foreach ( $locales as $slug ) {
			$locale = GP_Locales::by_slug($slug->locale);
			echo "<option value=\"$locale->slug\">$locale->native_name | $locale->english_name</option>\n";
		}
		?>
</select>
		<br/>
		<input type="checkbox" name="export[originals]" value="on">
		<label for="export[originals]"><?php _e('Also export original English texts'); ?></label><br/>
</li></ul>
	</div>

		<p>
		<label for="export[elggpath]"><?php _e('Type the name for the file to download'); ?></label><br/>
		<input type="text" name="export[elggpath]" id="elggpath" style="width:400px" value="<?php echo $export['elggpath']; ?>"><br/>
		<small><?php _e('You can leave this blank and the file will be called <i>elgg-languages.zip</i>.'); ?></small>
		</p>
<?php
/**
		<p>
		<label for="export[version]"><?php _e('The version of Elgg to export for'); ?></label><br/>
		<?php echo $version_dropdown; ?>
		</p>
 */
?>
	</dd>
</dl>

<p>
	<input type="submit" name="submit" value="<?php echo esc_attr(__('Export')); ?>" id="submit" />
</p>
</form>
<?php else: ?>
    <p><?php _e('There are no Elgg projects to export from'); ?></p>
<?php endif; ?>
<?php
gp_tmpl_footer();