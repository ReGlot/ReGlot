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
                    <li><?php gp_link_project($project, esc_html($project->name)); ?> <?php gp_link_project_edit($project, null, array('class' => 'bubble')); ?> <?php gp_link_project_delete($project, null, array('class' => 'bubble')); ?> <?php if ($project->active) echo '<span class="active bubble">Active</span>'; ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
        <?php if (GP::$user->current()->can('write', 'project')): ?>
            <p class="actionlist secondary"><?php gp_link(gp_url_project('-new'), __('Create a New Project')); ?></p>
        <?php endif; ?>
    </article>
</section>
<?php gp_tmpl_footer(); ?>