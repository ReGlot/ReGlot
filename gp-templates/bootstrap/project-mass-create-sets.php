<?php
gp_title(sprintf(__('Mass-create Translation Sets &lt; %s &lt; GlotPress'), $project->name));
gp_breadcrumb_project($project);
wp_enqueue_script('mass-create-sets-page');
wp_localize_script('mass-create-sets-page', '$gp_mass_create_sets_options', array(
    'url' => gp_url_join(gp_url_current(), 'preview'),
    'loading' => __('Loading translation sets to create&hellip;'),
));
gp_tmpl_header();
?>
<section id="content">
    <h2><?php echo esc_html($project->name); ?></h2>
    <ul class="nav nav-tabs">
        <li><?php gp_link_project($project, __('Cancel'), array('class' => 'btn btn-warning')); ?></li>
        <li class="active"><a href="#"><?php _e('Mass-create Translation Sets'); ?></a></li>
    </ul>

    <p><?php _e('Here you can mass-create translation sets in this project.
The list of translation sets will be mirrored with the sets of a project you choose.
Usually this is one of the parent projects.'); ?></p>
    <form action="<?php echo esc_url(gp_url_current()); ?>" method="post" class="form-horizontal">
        <div class="control-group">
            <label class="control-label" for="project_id"><?php _e('Project to take translation sets from:'); ?></label>
            <div class="controls">
                <?php echo gp_projects_dropdown('project_id', null); ?>
            </div>
        </div>
        <div id="preview"></div>
        <div class="control-group">
            <div class="controls">
                <input type="submit" name="submit" value="<?php echo esc_attr(__('Create Translation Sets')); ?>" id="submit" class="btn btn-primary" />
                <input type="hidden" name="action" value="add-validator" />
            </div>
        </div>
    </form>
</section>
<?php gp_tmpl_footer(); ?>