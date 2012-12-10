    <div class="control-group">
        <label class="control-label" for="set[locale]"><?php _e('Locale'); ?></label>
        <div class="controls">
            <?php echo gp_locales_dropdown('set[locale]', $set->locale, array('class' => 'input-xlarge')); ?>
            <span class="help-inline"><a href="#" id="copy"><?php _e('Use as name'); ?></a></span>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="set[name]"><?php _e('Name'); ?></label>
        <div class="controls">
            <input type="text" name="set[name]" value="<?php echo esc_html($set->name); ?>" id="set[name]" placeholder="<?php _e('Name'); ?>">
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="set[slug]"><?php _e('Slug'); ?></label>
        <div class="controls">
            <input type="text" name="set[slug]" value="<?php echo esc_html($set->slug ? $set->slug : 'default' ); ?>" id="set[slug]" placeholder="<?php _e('Slug'); ?>">
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="set[project_id]"><?php _e('Project'); ?></label>
        <div class="controls">
            <?php echo gp_projects_dropdown('set[project_id]', $set->project_id, array('class' => 'input-xlarge')); ?>
        </div>
    </div>
<?php echo gp_js_focus_on('set[locale]'); ?>
<script type="text/javascript">
    jQuery(function($){
        $('#copy').click(function() {
            $('#set\\[name\\]').val($('#set\\[locale\\] option:selected').html().replace(/^\S+\s+\S+\s+/, '').replace(/&mdash|—/, ''));
            $('#set\\[slug\\]').val($('#set\\[locale\\] option:selected')[0].value);
            return false;
        });
    });
</script>
