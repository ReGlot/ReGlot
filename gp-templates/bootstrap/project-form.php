    <div class="control-group">
        <label class="control-label" for="project[name]"><?php _e('Name'); ?></label>
        <div class="controls">
            <input type="text" name="project[name]" value="<?php echo esc_html( $project->name ); ?>" id="project[name]" placeholder="" class="input-xlarge">
        </div>
    </div>
    <!-- TODO: make slug edit WordPress style -->
    <div class="control-group">
        <label class="control-label" for="project[slug]"><?php _e('Slug'); ?></label>
        <div class="controls">
            <input type="text" name="project[slug]" value="<?php echo esc_html( $project->slug ); ?>" id="project[slug]" placeholder="" class="input-xlarge">
            <span class="help-inline"><?php _e('If you leave the slug empty, it will be derived from the name.'); ?></span>
        </div>
    </div> 
    <div class="control-group">
        <label class="control-label" for="project[description]"><?php _e('Description'); ?></label>
        <div class="controls">
            <textarea name="project[description]" rows="8" id="project[description]" class="input-xxlarge"><?php echo esc_html( $project->description ); ?></textarea>
            <span class="help-block"><?php _e('can include HTML');  ?></span>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="project[source_url_template]"><?php _e('Source file URL'); ?></label>
        <div class="controls">
            <input type="text" name="project[source_url_template]" value="<?php echo esc_html( $project->source_url_template ); ?>" id="project[source_url_template]" placeholder="" class="input-xxlarge" >
            <span class="help-block"><?php _e('URL to a source file in the project. You can use <code>%file%</code> and <code>%line%</code>. Ex. <code>http://trac.example.org/browser/%file%#L%line%</code>'); ?></span>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="project[parent_project_id]"><?php _e('Parent Project');  ?></label>
        <div class="controls">
            <?php echo gp_projects_dropdown( 'project[parent_project_id]', $project->parent_project_id, array('class' => 'input-xlarge')); ?>
        </div>
    </div>
    <div class="control-group">
        <div class="controls">
            <input type="checkbox" id="project[active]" name="project[active]" <?php gp_checked( $project->active ); ?> />
            <span class="help-inline"><?php _e('Active'); ?></span>
        </div>
    </div>
    <?php echo gp_js_focus_on( 'project[name]' ); ?>
