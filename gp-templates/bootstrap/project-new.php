<?php
gp_title(__('Create New Project'));
gp_breadcrumb(array(__('New Project'),));
gp_tmpl_header();
?>
<section id="content">
    <h2><?php _e('Create New Project'); ?></h2>
    <form action="" method="post" class="form-horizontal">
        <?php gp_tmpl_load('project-form', get_defined_vars()); ?>
        <div class="control-group">
            <div class="controls">
                <input type="submit" name="submit" value="<?php echo esc_attr(__('Create')); ?>" id="submit" class="btn btn-primary" />
                <a href="javascript:history.back();" class="btn">Cancel</a>
            </div>
        </div>
    </form>
</section>
<?php gp_tmpl_footer(); ?>