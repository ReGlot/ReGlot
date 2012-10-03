<?php
gp_title(__('Create New Project'));
gp_breadcrumb(array(__('New Project'),));
gp_tmpl_header();
?>
<section id="content">
    <h2><?php _e('Create New Project'); ?></h2>
    <article>
        <form action="" method="post">
            <?php gp_tmpl_load('project-form', get_defined_vars()); ?>
            <p>
                <input type="submit" name="submit" value="<?php echo esc_attr(__('Create')); ?>" id="submit" />
                <span class="or-cancel">or <a href="javascript:history.back();">Cancel</a></span>
            </p>
        </form>
    </article>
</section>
<?php gp_tmpl_footer(); ?>