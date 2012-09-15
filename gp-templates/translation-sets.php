<?php if ( $translation_sets ): ?>
<div id="translation-sets" style="width:<?php echo $sub_projects ? 70 : 100; ?>%;">
	<h3>Translations</h3>
	<table class="translation-sets">
		<thead>
			<tr>
				<th><?php _e( 'Language' ); ?></th>
				<th><?php echo _x( '%', 'language translation percent header' ); ?></th>
				<th><?php _e( 'Translated' ); ?></th>
				<th><?php _e( 'Untranslated' ); ?></th>
				<th><?php _e( 'Waiting' ); ?></th>
				<th><?php _e( 'Actions' ); ?></th>
			</tr>
		</thead>
		<tbody>
		<?php foreach( $translation_sets as $set ): ?>
			<tr class="<?php echo $parity(); ?>">
				<td>
					<strong><?php gp_link( gp_url_project( $set->path, gp_url_join( $set->locale ? $set->locale : '~', $set->slug ? $set->slug : '~' ) ), $set->display_name ); ?></strong>
					<?php if ($set->current_count >= $set->all_count ) { ?>
						<span class="bubble morethan99 tooltip">
							Complete<span>All translations have been completed</span>
						</span>
					<?php } else if ($set->current_count > $set->all_count * 0.9 ) { ?>
						<span class="bubble morethan90 tooltip">
							&gt;90%<span>Project translation is almost complete</span>
						</span>
					<?php } else if ($set->current_count >= $set->all_count * 0.8 ) { ?>
						<span class="bubble morethan80 tooltip">
							&gt;80%<span>Project translation is more than 80% complete</span>
						</span>
					<?php } else if ($set->current_count < $set->all_count * 0.4 ) { ?>
						<span class="bubble lessthan20 tooltip">
							&lt;40%<span class="tooltip">Less than 40% of this project has been translated</span>
						</span>
					<?php } else { ?>
						<span class="bubble morethan20 tooltip">
							&nbsp;&ReverseEquilibrium;&nbsp;<span class="tooltip">Translation is in progress</span>
						</span>
						
					<?php } ?>
				</td>
				<td class="stats percent"><?php echo $set->percent_translated; ?></td>
				<td class="stats translated" title="translated"><?php gp_link( gp_url_project( $project, gp_url_join( $set->locale, $set->slug ),
							array('filters[translated]' => 'yes', 'filters[status]' => 'current') ), "$set->current_count of $set->all_count" ); ?></td>
				<td class="stats untranslated" title="untranslated"><?php gp_link( gp_url_project( $project, gp_url_join( $set->locale, $set->slug ),
							array('filters[status]' => 'untranslated' ) ), $set->untranslated_count ); ?></td>
				<td class="stats waiting"><?php gp_link( gp_url_project( $project, gp_url_join( $set->locale, $set->slug ),
							array('filters[translated]' => 'yes', 'filters[status]' => 'waiting') ), $set->waiting_count ); ?></td>
				<td align="center">
					<?php gp_link_set_edit($set, $project, null, array('class' => 'bubble')); ?>
					<?php gp_link_set_delete($set, $project, null, array('class' => 'bubble')); ?>
					<?php do_action( 'project_template_translation_set_extra', $set, $project ); ?>
				</td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
</div>
<?php elseif ( !$sub_projects ): ?>
	<p><?php _e('There are no translations of this project.'); ?></p>
<?php endif; ?>