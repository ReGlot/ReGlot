<?php
gp_title(__('Settings', 'glotpress'));
gp_tmpl_header();
?>
<h2><?php _e('Site Settings', 'glotpress'); ?></h2>
<form action="" method="post" class="secondary">
<input type="hidden" name="settings[gp_handle_settings]" value="on">
<dl>
	<dt><h3><?php echo __('Import/Export', 'glotpress'); ?></h3></dt>
	<dd>
		<label for="settings[default_format]"><?php _e('Default Format', 'glotpress'); ?></label>
		<?php echo gp_select_format('settings[default_format]', array(), $settings['default_format']); ?><br/>
	</dd>
	<dt><h3><?php echo __('Project Management', 'glotpress'); ?></h3></dt>
	<dd>
		<p>
		<input type="checkbox" name="settings[default_recursive_sets]" value="on"<?php gp_checked($settings['default_recursive_sets'] == 'on'); ?>>
		<label for="settings[default_recursive_sets]"><?php echo __('Recursively create translation sets in subprojects by default', 'glotpress'); ?></label><br/>
		<small><?php _e('You can still change this option before every individual translation set creation', 'glotpress') ?></small>
		</p>
	</dd>
</dl>
<p>
	<input type="submit" name="submit" value="<?php echo esc_attr(__('Save', 'glotpress')); ?>" id="submit" />
</p>
</form>
<?php
gp_tmpl_footer();