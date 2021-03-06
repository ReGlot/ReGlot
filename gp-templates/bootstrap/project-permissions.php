<?php
gp_title(__('Permissions'));
gp_breadcrumb_project($project);
gp_tmpl_header();
?>
<section id="content">
    <h2><?php echo esc_html($project->name); ?> :: <?php _e('Permissions'); ?></h2>
    <ul class="nav nav-tabs">
        <li><?php gp_link_project($project, __('<i class="icon-chevron-left icon-white"></i> Back'), array('class' => 'btn btn-warning')); ?></li>
        <li class="active"><a href="#"><?php _e('Validators'); ?></a></li>
        <?php if (count($permissions) + count($parent_permissions) > 10): ?>
            <li><a href="#add" onclick="jQuery('#user_login').focus(); return false;" class="secondary">Add &rarr;</a></li>
        <?php endif; ?>
    </ul>
    <?php if ($permissions): ?>
        <?php if ($parent_permissions): ?>
            <h4 id="validators"><?php _e('Validators for this project'); ?></h4>
        <?php endif; ?>
        <ul class="permissions">
            <?php foreach ($permissions as $permission): ?>
                <li>
                    <span class="permission-action"><?php _e('user'); ?></span>
                    <span class="user"><?php echo esc_html($permission->user->user_login); ?></span>
                    <span class="permission-action">can <?php echo esc_html($permission->action); ?> strings with locale</span>
                    <span class="user"><?php echo esc_html($permission->locale_slug); ?></span>
                    <span class="permission-action">and slug</span>
                    <span class="user"><?php echo esc_html($permission->set_slug); ?></span>
                    <a href="<?php echo gp_url_join(gp_url_current(), '-delete/' . $permission->id); ?>" class="label label-important"><?php _e('Remove'); ?></a>
                </li>
            <?php endforeach; ?>
        </ul>	
    <?php endif; ?>
    <?php if ($parent_permissions): ?>
        <h4 id="validators"><?php _e('Validators for parent projects'); ?></h4>
        <ul class="permissions">		
            <?php foreach ($parent_permissions as $permission): ?>
                <li>
                    <span class="permission-action"><?php _e('user'); ?></span>
                    <span class="user"><?php echo esc_html($permission->user->user_login); ?></span>
                    <span class="permission-action">can <?php echo esc_html($permission->action); ?> strings with locale</span>
                    <span class="user"><?php echo esc_html($permission->locale_slug); ?></span>
                    <span class="permission-action">and slug</span>
                    <span class="user"><?php echo esc_html($permission->set_slug); ?></span>
                    <span class="permission-action">in the project </span>
                    <span class="user"><?php gp_link_project($permission->project, esc_html($permission->project->name)); ?></span>
                </li>
            <?php endforeach; ?>
        </ul>				
    <?php endif; ?>
    <?php if (!$permissions && !$parent_permissions): ?>
        <strong><?php _e('No validators defined for this project.'); ?></strong>
    <?php endif; ?>
    <ul class="nav nav-tabs">
        <li class="active"><a href="#"><?php _e('Add a validator for this project'); ?></a></li>
    </ul>
    <form action="" method="post" class="form-horizontal">
        <div class="control-group">
            <label class="control-label" for="user_login"><?php _e('Username:'); ?></label>
            <div class="controls">
                <input type="text" name="user_login" value="" id="user_login" placeholder="<?php echo __('Username'); ?>">
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="locale"><?php _e('Locale:'); ?></label>
            <div class="controls">
                <?php echo gp_locales_dropdown('locale'); ?>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="set-slug"><?php _e('Translation set slug:'); ?></label>
            <div class="controls">
                <input type="text" name="set-slug" value="default" id="set-slug">
            </div>
        </div>
        <div class="control-group">
            <div class="controls">
                <input type="submit" name="submit" value="<?php echo esc_attr(__('Add')); ?>" id="submit" class="btn btn-primary" />
                <input type="hidden" name="action" value="add-validator" />
            </div>
        </div>
    </form>
</section>
<?php gp_tmpl_footer(); ?>