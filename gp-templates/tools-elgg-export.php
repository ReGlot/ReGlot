<?php
wp_enqueue_script('elgg_import_export');
gp_title(__('Tools'));
gp_tmpl_header();
$project_dropdown1 = gp_projects_dropdown('export[elggcoreproject]', $export['elggcoreproject'], array(), 'Select an Elgg project', true);
$project_dropdown2 = gp_projects_dropdown('export[elgg3rdproject]', $export['elgg3rdproject'], array(), 'No 3rd Party project', true);
$version_dropdown = gp_select('export[version]', array('' => '&mdash; Choose a version &mdash;', '2' => 'Version 1.9 and later', '1' => 'Version 1.8 and earlier'), $export['version']);
?>
<h2><?php _e('GlotPress Tools'); ?></h2>

<form action="" method="post" class="filters-toolbar" enctype="multipart/form-data">
<input type="hidden" name="export[gp_handle_settings]" value="on">
<input type="hidden" name="export[project_selection]" value="">
<input type="hidden" name="export[locale_selection]" value="">
<dl>
	<dt><h3><?php _e('Elgg Package Export'); ?></h3></dt>
	<dd>
		<p><?php _e('Import all language data for an Elgg installation. The ZIP file should contain a folder name elgg-&lt;version&gt; and the whole Elgg installation or just the language data below it.
			The mod folders should include the manifest.xml file, used to get data about each plugin. The core plugins are imported as subprojects into an Elgg v&lt;version&gt; project, while all other
			plugins are imported into a top level Third Party Elgg Plugins project. The en locale is used for originals, any other locale is imported as translations.'); ?></p>
	<div>
	<a href="#" class="revealing projects"><?php _e('Projects &darr;'); ?></a> <span class="separator">&bull;</span>
	<a href="#" class="revealing locales"><?php _e('Locales &darr;'); ?></a> <strong class="separator"></strong>
	</div>

	<div class="projects hidden clearfix">
			<ul class="filters-expanded selection-expanded">
				<li><select id="select1_project" multiple="multiple" class="elgg-input-dropdown languagepacks-select">
				</select>
				</li>
				<li><div class="languagepacks-buttons">
<p><input type="button" id="add_project" value="Add Selected" class="elgg-button">
</p><p><input type="button" id="add-all_project" value="Add All" class="elgg-button">
</p><p><input type="button" id="remove-all_project" value="Remove All" class="elgg-button">
</p><p><input type="button" id="remove_project" value="Remove Selected" class="elgg-button">
</p></div></li><li><select id="select2_project" multiple="multiple" class="elgg-input-dropdown languagepacks-select">
		<?php
		$projects = GP::$project->by_path('elgg')->sub_projects();
		foreach ( $projects as $project ) {
			echo "<option value=\"$project->id\">$project->name</option>\n";
		}
		$projects = GP::$project->by_path('elgg3rd')->sub_projects();
		foreach ( $projects as $project ) {
			echo "<option value=\"$project->id\">(3) $project->name</option>\n";
		}
		?>
</select>
</li></ul>		
	</div>
	<div class="locales hidden clearfix">
			<ul class="filters-expanded selection-expanded">
				<li><select id="select1_locale" multiple="multiple" class="elgg-input-dropdown languagepacks-select">
				</select>
				</li>
				<li><div class="languagepacks-buttons">
<p><input type="button" id="add_locale" value="Add Selected" class="elgg-button">
</p><p><input type="button" id="add-all_locale" value="Add All" class="elgg-button">
</p><p><input type="button" id="remove-all_locale" value="Remove All" class="elgg-button">
</p><p><input type="button" id="remove_locale" value="Remove Selected" class="elgg-button">
</p></div></li><li><select id="select2_locale" multiple="multiple" class="elgg-input-dropdown languagepacks-select">
		<?php
		$locales = GP::$translation_set->locales_in_use();
		foreach ( $locales as $slug ) {
			$locale = GP_Locales::by_slug($slug->locale);
			echo "<option value=\"$locale->slug\">$locale->native_name | $locale->english_name</option>\n";
		}
		?>
</select>
</li></ul>
	</div>

		<p>
		<label for="export[elggpath]"><?php _e('Type the name for the file to download'); ?></label><br/>
		<input type="text" name="export[elggpath]" id="elggpath" style="width:400px" value="<?php echo $export['elggpath']; ?>"><br/>
		<small><?php _e('You can leave this blank and the file will be called <i>elgg-languages.zip</i>.'); ?></small>
		</p>
		<p>
		<input type="checkbox" name="export[originals]" value="on">
		<label for="export[originals]"><?php _e('Export original English texts with this language pack'); ?></label><br/>
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
<?php
gp_tmpl_footer();