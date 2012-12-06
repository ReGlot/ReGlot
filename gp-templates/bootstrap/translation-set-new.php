<?php
gp_title(__('Create New Translation Set'));
$project ? gp_breadcrumb_project($project) : gp_breadcrumb(array(__('New Translation Set')));
gp_tmpl_header();
?>
<section id="content">
    <h2><?php _e('Create New Translation Set'); ?></h2>
    <article>
        <form action="" method="post">
            <?php gp_tmpl_load('translation-set-form', get_defined_vars()); ?>
            <p>
                <label for="set[recursive]"><?php _e('Recursively create sets in subprojects'); ?></label>
                <input type="checkbox" id="set[recursive]" name="set[recursive]" <?php gp_checked(gp_get_option('default_recursive_sets') == 'on'); ?> />
            </p>
            <p>
                <input type="submit" name="submit" value="<?php echo esc_attr(__('Create')); ?>" id="submit" />
                <span class="or-cancel">or <a href="javascript:history.back();">Cancel</a></span>
            </p>
        </form>
    </article>
</section>
<?php gp_tmpl_footer(); ?>