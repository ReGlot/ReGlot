<?php
gp_title(__('Tools', 'glotpress'));
gp_tmpl_header();
?>
<h2><?php _e('External Tools', 'glotpress'); ?></h2>

<?php if ( empty($tools_config) ) { ?>
    <p><?php _e('Sorry! There are no tools available to you.', 'glotpress'); ?></p>
<?php } else { ?>
    <?php foreach ( $tools_config as $section => $tools ) { ?>
        <h3><?php echo $section; ?></h3>
        <ul>
        <?php foreach ( $tools as $tool ) { ?>
            <?php if ( !$tool['admin_only'] || GP::$user->current()->admin() ) { ?>
            <li>
                <a href="<?php echo gp_url_join('tool', $tool['link']); ?>"><?php echo $tool['title'] ?></a>: <?php echo $tool['description']; ?>
            </li>
            <?php } ?>
        <?php } ?>
        </ul>
    <?php } ?>
<?php } ?>
<?php
gp_tmpl_footer();