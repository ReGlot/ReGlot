<ul class="nav nav-tabs">
    <!-- CHANGE LINK GENERATION FOR BACK-to-PROJECT-->
    <li><a href="javascript:history.back();" class="btn btn-warning"><?php _e('<i class="icon-chevron-left icon-white"></i> Cancel'); ?></a></li>
    <li class="active"><a href="#"><?php echo sprintf($kind == 'originals' ? __('Import Originals into %s') : __('Import Translations for %s'), $project->name); ?></a></li>
</ul>
<form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
    <div class="control-group">
        <label class="control-label" for="import-file"><?php _e('Import File:'); ?></label>
        <div class="controls">
            <input type="file" name="import-file" id="import-file" />
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="format"><?php _e('Format:'); ?></label>
        <div class="controls">
            <?php echo gp_select_format('format', array('class' => 'input-xxlarge')); ?>
        </div>
    </div>
    <div class="control-group">
        <div class="controls">
            <input type="submit" name="submit" value="<?php echo esc_attr(__('Import')); ?>"" id="submit" class="btn btn-primary" />
        </div>
    </div>
</form>
