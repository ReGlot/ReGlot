<?php
gp_title(__('Projects'));
gp_breadcrumb(array(__('Projects')));
wp_enqueue_script('confirm');
gp_tmpl_header();
?>
<section id="content">
    <h2><?php _e('Projects') ?></h2>
    <article>
        <?php if (empty($projects)): ?>
            <p><?php _e('No projects were found!'); ?></p>
        <?php else: ?>
            <ul>
                <?php foreach ($projects as $project): ?>
                    <li><?php gp_link_project($project, esc_html($project->name)); ?> <?php gp_link_project_edit($project, null, array('class' => 'label label-info')); ?> <?php gp_link_project_delete($project, null, array('class' => 'label label-important')); ?> <?php if ($project->active) echo '<span class="label label-warning">Active</span>'; ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
        <?php if (GP::$user->current()->can('write', 'project')): ?>
            <p class="actionlist secondary"><?php gp_link(gp_url_project('-new'), __('Create a New Project')); ?></p>
            <a href="/projects/-new" class="btn btn-primary btn-large">
                Create a New Project
            </a>
        <?php endif; ?>
    </article>
</section>
<?php gp_tmpl_footer(); ?>