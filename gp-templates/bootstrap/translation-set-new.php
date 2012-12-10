<?php
gp_title(__('Create New Translation Set'));
$project ? gp_breadcrumb_project($project) : gp_breadcrumb(array(__('New Translation Set')));
gp_tmpl_header();
?>
<section id="content">
    <h2><?php echo esc_html($project->name); ?></h2>
    <ul class="nav nav-tabs">
<!--        <li><?php gp_link_project($project, esc_html($project->name)); ?></li> <li><?php gp_link_project($project, __('Cancel'), array('class' => 'btn btn-warning')); ?></li>-->
        <li><a href="javascript:history.back();" class="btn btn-warning"><i class="icon-chevron-left icon-white"></i> <?php echo __('Cancel'); ?></a></li>
        <li class="active"><a href="#"><?php _e('Create New Translation Set'); ?></a></li>
    </ul>
    <form action="" method="post" class="form-horizontal">
        <?php gp_tmpl_load('translation-set-form', get_defined_vars()); ?>
        <div class="control-group">
            <div class="controls">
                <input type="checkbox" id="set[recursive]" name="set[recursive]" <?php gp_checked(gp_get_option('default_recursive_sets') == 'on'); ?> />
                <span class="help-inline"><?php _e('Recursively create sets in subprojects'); ?></span>
            </div>
        </div>
        <div class="control-group">
            <div class="controls">
                <input type="submit" name="submit" value="<?php echo esc_attr(__('Create')); ?>" id="submit" class="btn btn-primary" />
                <a href="javascript:history.back();" class="btn">Cancel</a>
            </div>
        </div>
    </form>
</section>
<?php gp_tmpl_footer(); ?>