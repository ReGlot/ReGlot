<?php
gp_title(__('Projects'));
gp_breadcrumb(array(__('Projects')));
wp_enqueue_script('confirm');
gp_tmpl_header();
?>
<section id="content">
    <h2><?php _e('Projects') ?></h2>
    <ul class="nav nav-tabs">
        <li class="active"><a href="#"><?php _e('Overview'); ?></a></li>
        <li><a href="#"><?php _e('Stats'); ?></a></li>
    </ul>
    <?php if (empty($projects)): ?>
        <p><?php _e('No projects were found!'); ?></p>
    <?php else: ?>
        <ul class="nav nav-pills nav-stacked project-list">
            <?php if (GP::$user->current()->can('write', 'project')): ?>
                <li class="new-project clearfix"><?php gp_link(gp_url_project('-new'), __('Create a New Project'), array('class' => 'btn btn-primary btn-large')); ?></li>
                <!-- ADD to tab <i class="icon-plus-sign icon-white"></i>-->
            <?php endif; ?>
            <?php foreach ($projects as $project): ?>
                <li class="clearfix">
                    <?php gp_link_project($project, esc_html($project->name), array('class' => 'project-title')); ?>
                    <?php if ($project->active) echo '<span class="label label-warning">Active</span>'; ?>
                    <?php gp_link_project_edit($project, null, array('class' => 'label label-info')); ?>
                    <?php gp_link_project_delete($project, null, array('class' => 'label label-important')); ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</section>
<?php gp_tmpl_footer(); ?>