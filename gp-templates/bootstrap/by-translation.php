<?php
gp_title(__('Translations'));
gp_breadcrumb(array(__('Translations')));
//wp_enqueue_script('confirm');
gp_tmpl_header();
?>
<section id="content">
    <h2><?php _e('Translations') ?></h2>
    <?php if (empty($bundles)): ?>
        <p><?php _e('No translations were found!'); ?></p>
    <?php else: ?>
        <ul class="nav nav-tabs">
            <?php
            switch ($kind) {
                case 'slugs':
                    echo '<li>';
                    gp_link(gp_url_by_translation('locales'), __('Showing by Locale'));
                    echo '</li><li class="active">';
                    gp_link(gp_url_by_translation('slugs'), __('Show by Slug'));
                    echo '</li><li>';
                    gp_link(gp_url_by_translation('both'), __('Show by Both'));
                    echo '</li>';
                    break;
                case 'locales':
                    echo '<li class="active">';
                    gp_link(gp_url_by_translation('locales'), __('Showing by Locale'));
                    echo '</li><li>';
                    gp_link(gp_url_by_translation('slugs'), __('Show by Slug'));
                    echo '</li><li>';
                    gp_link(gp_url_by_translation('both'), __('Show by Both'));
                    echo '</li>';
                    break;
                case 'both':
                    echo '<li>';
                    gp_link(gp_url_by_translation('locales'), __('Showing by Locale'));
                    echo '</li><li>';
                    gp_link(gp_url_by_translation('slugs'), __('Show by Slug'));
                    echo '</li><li class="active">';
                    gp_link(gp_url_by_translation('both'), __('Show by Both'));
                    echo '</li>';
                    break;
            }
            if (GP::$user->logged_in()) {
                ?>
                <li><?php gp_link(gp_url_user_translations(), __('Show Your Own'), array('class' => 'btn btn-info')); ?></li>
                <?php
            }
            ?>
        </ul>

        <ul>
            <?php foreach ($bundles as $bundle): ?>
                <li>
                    <?php
                    switch ($kind) {
                        case 'slugs':
                            gp_link("/by-translation/slug/$bundle->slug", esc_html($bundle->name));
                            echo " ($bundle->slug)";
                            break;
                        case 'locales':
                            $locale = GP_Locales::by_slug($bundle->locale);
                            gp_link("/by-translation/locale/$bundle->locale", esc_html($locale->native_name . ' / ' . $locale->english_name));
                            echo " ($locale->slug)";
                            break;
                        case 'both':
                            echo esc_html($bundle->name);
                            echo " ($bundle->slug) &rarr; ";
                            $locale = GP_Locales::by_slug($bundle->slug);
                            echo esc_html($locale->native_name . ' / ' . $locale->english_name);
                            echo " ($locale->slug) : ";
                            gp_link("/by-translation/both/$bundle->locale/$bundle->slug", __('view'));
                            break;
                    }
                    ?>
                    <?php // gp_link_project_edit($bundle, null, array('class' => 'bubble')); ?>
                    <?php // gp_link_project_delete($project, null, array('class' => 'bubble')); ?>
                    <?php // if ( $project->active ) echo '<span class="active bubble">Active</span>'; ?>
                </li>
            <?php endforeach; ?>
        <?php endif; ?>
    </ul>
</section>
<?php gp_tmpl_footer(); ?>