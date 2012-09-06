<?php
gp_title(__('Site Settings'));
gp_tmpl_header();
?>
<h2><?php _e('Site Settings'); ?></h2>
<form action="" method="post" class="secondary">
<dl>
	<dt><h3><?php echo __('Import/Export'); ?></h3></dt>
	<dd>
		<label for="settings[default_format]"><?php _e('Default Format'); ?></label>
		<?php echo gp_select_format('settings[default_format]', array(), $settings['default_format']); ?>
	</dd>
</dl>
<p>
	<input type="submit" name="submit" value="<?php echo esc_attr(__('Save')); ?>" id="submit" />
</p>
</form>
<?php
gp_tmpl_footer();