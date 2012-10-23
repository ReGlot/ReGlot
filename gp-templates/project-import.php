<h2><?php echo sprintf($kind == 'originals' ? __('Import Originals into %s') : __('Import Translations for %s'), $project->name); ?></h2>
<article>
    <form action="" method="post" enctype="multipart/form-data">
        <dl>
            <dt><label for="import-file"><?php _e('Import File:'); ?></label></dt>
            <dd><input type="file" name="import-file" id="import-file" /></dd>
            <dt><label for="format"><?php _e('Format:'); ?></label></dt>
            <dd><?php echo gp_select_format('format'); ?></dd>
        <dt><input type="submit" value="<?php echo esc_attr(__('Import')); ?>"></dt>
    </form>
</article>
