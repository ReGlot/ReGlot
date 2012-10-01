<?php if ( $translation_sets ): ?>
<div id="translation-sets" style="width:<?php echo $sub_projects ? 70 : 100; ?>%;">
	<h3>Translations</h3>
	<table class="translation-sets">
		<thead>
			<tr>
				<th><?php _e( 'Language' , 'glotpress'); ?></th>
				<th><?php echo _x( '%', 'language translation percent header' , 'glotpress'); ?></th>
                <th><?php _e( 'All' , 'glotpress'); ?></th>
				<th><?php _e( 'Translated (of total)' , 'glotpress'); ?></th>
				<th><?php _e( 'Untranslated' , 'glotpress'); ?></th>
				<th><?php _e( 'Waiting' , 'glotpress'); ?></th>
<?php if ( GP::$user->logged_in() ) { ?>
				<th><?php _e( 'Own (of translated)' , 'glotpress'); ?></th>
<?php } ?>
			</tr>
		</thead>
		<tbody>
		<?php foreach( $translation_sets as $set ): ?>
            <?php $row_url_join_base = gp_url_join($set->locale ? $set->locale : '~', $set->slug ? $set->slug : '~', $suffix); ?>
			<tr class="<?php echo $parity(); ?>">
				<td width="28%">
					<strong><?php gp_link(gp_url_project($set->path, $row_url_join_base), $set->display_name); ?></strong>
					<?php if ($set->current_count >= $set->all_count ) { ?>
						<span class="bubble morethan99">
							Complete
						</span>
					<?php } else if ($set->current_count > $set->all_count * 0.9 ) { ?>
						<span class="bubble morethan90">
							&gt;90%
						</span>
					<?php } else if ($set->current_count >= $set->all_count * 0.8 ) { ?>
						<span class="bubble morethan80">
							&gt;80%
						</span>
					<?php } else if ($set->current_count <= 0.0 ) { ?>
						<span class="bubble lessthan20">
							EMPTY
						</span>
					<?php } else if ($set->current_count < $set->all_count * 0.4 ) { ?>
						<span class="bubble lessthan20">
							&lt;40%
						</span>
					<?php } else { ?>
						<span class="bubble morethan20">
							in progress
						</span>
					<?php } ?>
				</td>
				<td class="stats percent" width="12%"><?php echo $set->percent_translated; ?></td>
                <td class="stats total" title="total">
                    <?php
                    gp_link(gp_url_project($set->path, $row_url_join_base), $set->all_count);
                    ?></td>
				<td class="stats translated" title="translated" width="12%">
                    <?php
                    if ( $set->current_count ) {
                        gp_link(gp_url_project($set->path, $row_url_join_base, array('filters[translated]' => 'yes', 'filters[status]' => 'current')), "$set->current_count of $set->all_count");
                    } else {
                        echo "0 of $set->all_count";
                    }
                ?></td>
				<td class="stats untranslated" title="untranslated" width="12%">
                    <?php
                    if ( $set->untranslated_count ) {
                        gp_link(gp_url_project($set->path, $row_url_join_base, array('filters[status]' => 'untranslated')), $set->untranslated_count);
                    } else {
                        echo '0';
                    }
                    ?></td>
				<td class="stats waiting" title="waiting" width="12%">
                    <?php
                    if ( $set->waiting_count ) {
                        gp_link(gp_url_project($set->path, $row_url_join_base, array('filters[translated]' => 'yes', 'filters[status]' => 'waiting')), $set->waiting_count);
                    } else {
                        echo '0';
                    }
                    ?></td>
<?php if ( GP::$user->logged_in() ) { ?>
                <td class="stats own" title="yours" width="12%">
                    <?php
                    $by_you = GP::$translation->count_by_user(GP::$user->current()->id, $set->project_id, $set->translation_set_id, $locale_slug);
                    if ( $by_you ) {
                        gp_link(gp_url_project($set->path, $row_url_join_base, array('filters[translated]' => 'yes', 'filters[status]' => 'waiting')), "$by_you of $set->current_count");
                    } else {
                        echo '0';
                    }
                    ?></td>
<?php } ?>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
</div>
<?php elseif ( !$sub_projects ): ?>
	<p><?php _e('There are no translations of this project.', 'glotpress'); ?></p>
<?php endif; ?>