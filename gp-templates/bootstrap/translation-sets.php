<?php if ($translation_sets): ?>
    <div id="translation-sets" style="width:<?php echo $sub_projects ? 70 : 100; ?>%;">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#"><?php _e('Translations'); ?></a></li>
        </ul>
        <table class="table table-hover translation-sets">
            <thead>
                <tr>
                    <th><?php _e('Language'); ?></th>
                    <th><?php echo _x('%', 'language translation percent header'); ?></th>
                    <th><?php _e('All'); ?></th>
                    <th><?php _e('Untranslated'); ?></th>
                    <th><?php _e('Translated (of total)'); ?></th>
                    <th><?php _e('Waiting'); ?></th>
                    <?php if (GP::$user->logged_in()) { ?>
                        <th><?php _e('Own (of translated)'); ?></th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($translation_sets as $set): ?>
                    <?php $row_url_join_base = gp_url_join($set->locale ? $set->locale : '~', $set->slug ? $set->slug : '~', $suffix); ?>
                    <tr class="<?php echo $parity(); ?>">
                        <td>
                            <strong><?php gp_link(gp_url_project($set->path, $row_url_join_base), $set->display_name); ?></strong>
                            <?php if ($set->current_count >= $set->all_count) { ?>
                                <span class="badge badge-success">
                                    Complete
                                </span>
                            <?php } else if ($set->current_count > $set->all_count * 0.9) { ?>
                                <span class="badge badge-inverse">
                                    &gt;90%
                                </span>
                            <?php } else if ($set->current_count >= $set->all_count * 0.8) { ?>
                                <span class="badge badge-warning">
                                    &gt;80%
                                </span>
                            <?php } else if ($set->current_count <= 0.0) { ?>
                                <span class="badge badge-important">
                                    EMPTY
                                </span>
                            <?php } else if ($set->current_count < $set->all_count * 0.4) { ?>
                                <span class="badge badge-important">
                                    &lt;40%
                                </span>
                            <?php } else { ?>
                                <span class="badge badge-important">
                                    in progress
                                </span>
                            <?php } ?>
                        </td>
                        <td class="stats percent"><?php echo $set->percent_translated; ?></td>
                        <td class="stats total" title="total">
                            <?php
                            gp_link(gp_url_project($set->path, $row_url_join_base), $set->all_count);
                            ?></td>
                        <td class="stats translated" title="translated" >
                            <?php
                            if ($set->current_count) {
                                gp_link(gp_url_project($set->path, $row_url_join_base, array('filters[translated]' => 'yes', 'filters[status]' => 'current')), "$set->current_count of $set->all_count");
                            } else {
                                echo "0 of $set->all_count";
                            }
                            ?></td>
                        <td class="stats untranslated" title="untranslated">
                            <?php
                            if ($set->untranslated_count) {
                                gp_link(gp_url_project($set->path, $row_url_join_base, array('filters[status]' => 'untranslated')), $set->untranslated_count);
                            } else {
                                echo '0';
                            }
                            ?></td>
                        <td class="stats waiting" title="waiting">
                            <?php
                            if ($set->waiting_count) {
                                gp_link(gp_url_project($set->path, $row_url_join_base, array('filters[translated]' => 'yes', 'filters[status]' => 'waiting')), $set->waiting_count);
                            } else {
                                echo '0';
                            }
                            ?></td>
                        <?php if (GP::$user->logged_in()) { ?>
                            <td class="stats own" title="yours">
                                <?php
                                $by_you = GP::$translation->count_by_user(GP::$user->current()->id, $set->project_id, $set->translation_set_id, $locale_slug);
                                if ($by_you) {
                                    gp_link(gp_url_project($set->path, $row_url_join_base, array('filters[translated]' => 'yes', 'filters[status]' => 'waiting')), "$by_you of $set->current_count");
                                } else {
                                    echo '0 of ' . ($set->current_count + $set->waiting_count);
                                }
                                ?></td>
                        <?php } ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php elseif (!$sub_projects): ?>
    <p><?php _e('There are no translations of this project.'); ?></p>
<?php endif; ?>