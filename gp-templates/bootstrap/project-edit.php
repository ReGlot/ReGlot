<?php
gp_title(__('Edit Project'));
gp_breadcrumb_project($project);
gp_tmpl_header();
?>
<section id="content">
    <h2><?php echo esc_html($project->name); ?></h2>
    <ul class="nav nav-tabs">
        <li><?php gp_link_project($project, __('<i class="icon-chevron-left icon-white"></i> Cancel'), array('class' => 'btn btn-warning')); ?></li>
        <li class="active"><a href="#"><?php _e('Edit project'); ?></a></li>
    </ul>
    <form action="" method="post" class="form-horizontal">
        <?php gp_tmpl_load('project-form', get_defined_vars()); ?>
        <div class="control-group">
            <div class="controls">
                <input type="submit" name="submit" value="<?php echo esc_attr(__('Save')); ?>" id="submit" class="btn btn-primary" />
                <a href="javascript:history.back();" class="btn"><?php echo __('Cancel'); ?></a>
            </div>
        </div>
    </form>
</section>
<?php gp_tmpl_footer(); ?>