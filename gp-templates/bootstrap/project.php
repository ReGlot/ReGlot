<?php
gp_title(esc_html($project->name));
gp_breadcrumb_project($project);
wp_enqueue_script('common');
wp_enqueue_script('confirm');
$edit_link = gp_link_project_edit_get($project, '<i class="icon-pencil"></i> edit');
$delete_link = gp_link_project_delete_get($project, '<i class="icon-trash"></i> delete');
$parity = gp_parity_factory();
gp_tmpl_header();
?>
<section id="content">
    <h2><?php echo esc_html($project->name); ?></h2>
    <ul class="nav nav-tabs">
        <li><a href="<?php echo gp_url_project() ?>" class="btn btn-warning"><i class="icon-chevron-left icon-white"></i> Back</a></li>
        <li class="active"><a href="#"><?php _e('Description'); ?></a></li>
        <li><?php echo $edit_link; ?></li>
        <li><?php echo $delete_link; ?></li>
        <?php if ($can_write): ?>
            <li class="dropdown">
                <a href="#" data-toggle="dropdown" class="dropdown-toggle"><?php _e('Project actions'); ?><b class="caret"></b></a>
                <ul class="dropdown-menu">
                    <li><?php gp_link(gp_url_project($project, '-originals'), __('View/Import Originals')); ?></li>
                    <li><?php gp_link(gp_url_project($project, array('-permissions')), __('Permissions')); ?></li>
                    <li><?php gp_link(gp_url_project('', '-new', array('parent_project_id' => $project->id)), __('New Sub-Project')); ?></li>
                    <li><?php gp_link(gp_url('/sets/-new', array('project_id' => $project->id)), __('New Translation Set')); ?></li>
                    <li><?php gp_link(gp_url_project($project, array('-mass-create-sets')), __('Mass-create Translation Sets')); ?></li>
                    <?php if ($translation_sets): ?>
                        <li class="divider"></li>
                        <li class="dropdown-submenu">
                            <a href="#" tabindex="-1" class="personal-options" id="personal-options-toggle"><?php _e('Personal project options'); ?></a>
                            <ul class="dropdown-menu">

                                <form action="<?php echo gp_url_project($project, '-personal'); ?>" method="post" class="form-horizontal">
                                    <li><label class="control-label" for="source-url-template"><?php _e('Source file URL'); ?></label></li></li>
                                    <li><input type="text" value="<?php echo esc_html($project->source_url_template()); ?>" name="source-url-template" id="source-url-template" placeholder="" class="input-large"></li>
                                    <li><span class="help-block"><?php _e('URL to a source file in the project. You can use <code>%file%</code> and <code>%line%</code>. Ex. <code>http://trac.example.org/browser/%file%#L%line%</code>'); ?></span></li>
                                    <li><input type="submit" name="submit" value="<?php echo esc_attr(__('Save')); ?>" id="submit" class="btn btn-primary" /></li>
                                </form>

                            </ul>
                        </li>
                    <?php endif; ?>
                </ul>
            </li>
        <?php endif; ?>
    </ul>
    <p class="description">
        <?php echo $project->description; ?>
    </p>
    <?php if ($sub_projects): ?>
        <div id="sub-projects"  style="width:<?php echo $translation_sets ? 20 : 100; ?>%;">
            <h3><?php _e('Sub-projects'); ?></h3>
            <dl>
                <?php foreach ($sub_projects as $sub_project): ?>
                    <dt>
                    <?php gp_link_project($sub_project, esc_html($sub_project->name)); ?>
                    <?php gp_link_project_edit($sub_project, null, array('class' => 'bubble')); ?>
                    <?php gp_link_project_delete($sub_project, null, array('class' => 'bubble')); ?>
                    <?php if ($sub_project->active) echo "<span class='active bubble'>Active</span>"; ?>
                    </dt>
                    <dd>
                        <?php
                        if ($translation_sets) {
                            echo esc_html(gp_html_excerpt($sub_project->description, 120));
                        } else {
                            echo esc_html($sub_project->description);
                        }
                        ?>
                    </dd>
                <?php endforeach; ?>
            </dl>
        </div>
    <?php endif; ?>

    <?php gp_tmpl_load('translation-sets', get_defined_vars()); ?>

    <div class="clear"></div>
</section>
<script type="text/javascript" charset="utf-8">
    $gp.showhide('a.personal-options', 'div.personal-options', {
        show_text: 'Personal project options &darr;',
        hide_text: 'Personal project options &uarr;',
        focus: '#source-url-template',
        group: 'personal'
    });
    $('div.personal-options').hide();
    $gp.showhide('a.project-actions', 'div.project-actions', {
        show_text: 'Project actions &darr;',
        hide_text: 'Project actions &uarr;',
        focus: '#source-url-template',
        group: 'project'
    });
</script>
<?php gp_tmpl_footer(); ?>
