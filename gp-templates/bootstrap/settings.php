<?php
gp_title(__('Settings'));
gp_tmpl_header();
?>
<section id="content">
    <h2><?php _e('Site Settings'); ?></h2>
    <article>
        <form action="" method="post" class="form-horizontal">
            <input type="hidden" name="settings[gp_handle_settings]" value="on">
            <h3><?php echo __('Import/Export'); ?></h3>
            <div class="control-group">
                <label class="control-label" for="settings[default_format]"><?php _e('Default Format'); ?></label>
                <div class="controls">
                    <?php echo gp_select_format('settings[default_format]', array(), $settings['default_format']); ?>
                </div>
            </div>
            <h3><?php echo __('Project Management'); ?></h3>
            <div class="control-group">
                <div class="controls">
                    <label class="checkbox">
                    <input type="checkbox" name="settings[default_recursive_sets]" value="on"<?php gp_checked($settings['default_recursive_sets'] == 'on'); ?>> <?php echo __('Recursively create translation sets in subprojects by default'); ?>
                    </label>
                    <span class="help-block"><?php _e('You can still change this option before every individual translation set creation') ?></span>
                </div>
            </div>
            <div class="control-group">
                <div class="controls">
                   <input type="submit" name="submit" value="<?php echo esc_attr(__('Save')); ?>" id="submit" class="btn btn-primary"/>
                </div>
            </div>
        </form>
    </article>
</section>
<?php gp_tmpl_footer(); ?>