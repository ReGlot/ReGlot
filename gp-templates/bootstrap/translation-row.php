<?php
$status_class = $t->translation_status? 'status-'.$t->translation_status : 'untranslated';
$warning_class = $t->warnings? 'has-warnings' : 'no-warnings';
$priority_class = 'priority-'.gp_array_get( GP::$original->get_static( 'priorities' ), $t->priority );
$priority_char = array(
    '-2' => array('&times;', 'transparent', '#ccc'),
    '-1' => array('&darr;', 'transparent', 'blue'),
    '0' => array('', 'transparent', 'white'),
    '1' => array('&uarr;', 'transparent', 'green'),
);
if ( $project ) {
	$row_project = $project;
} else {
	$row_project = GP::$project->get($t->project_id);
	$can_write = GP::$user->current()->can('write', 'project', $row_project->id);
}
if ( $translation_set ) {
	$row_translation_set = $translation_set;
} else {
	$row_translation_set = GP::$translation_set->get($t->translation_set_id);
	$can_approve = GP::$user->current()->can_approve($row_translation_set);
}
if ( $locale ) {
	$row_locale = $locale;
} else {
	$row_locale = GP_Locales::by_slug($row_translation_set->locale);
}
if ( $user ) {
	$row_user = $user;
} else {
	$row_user = GP::$user->by_login($t->user_login);
}

$url = gp_url_project($row_project, gp_url_join($locale_slug, $translation_set_slug, $kind));
$editorUrl = gp_url_project($row_project, gp_url_join($row_locale->slug, $row_translation_set->slug));
$set_priority_url = gp_url( '/originals/%original-id%/set_priority');
$discard_warning_url = gp_url_project($row_project, gp_url_join($row_locale->slug, $row_translation_set->slug, '-discard-warning'));
$set_status_url = gp_url_project($row_project, gp_url_join($row_locale->slug, $row_translation_set->slug, '-set-status'));
$bulk_action = gp_url_join($url, '-bulk');
?>
<tr class="preview <?php echo $parity().' '.$status_class.' '.$warning_class.' '.$priority_class ?>" id="preview-<?php echo $t->row_id ?>" row="<?php echo $t->row_id; ?>">
	<th scope="row" class="checkbox"><?php if ( $can_approve && $kind != 'u' ) : ?><input type="checkbox" name="selected-row[]" /><?php else: echo '&nbsp;'; endif; ?></th>
	<?php /*
	<td class="priority" style="background-color: <?php echo $priority_char[$t->priority][1] ?>; color: <?php echo $priority_char[$t->priority][2] ?>; text-align: center; font-size: 1.2em;" title="<?php echo esc_attr('Priority: '.gp_array_get( GP::$original->get_static( 'priorities' ), $t->priority )); ?>">
	*/ ?>
	<td class="priority" title="<?php echo esc_attr('Priority: '.gp_array_get( GP::$original->get_static( 'priorities' ), $t->priority )); ?>">
	   <?php echo $priority_char[$t->priority][0] ?>
	</td>
	<td class="original">
		<?php echo prepare_original( esc_translation( $t->singular ) ); ?>
		<?php if ( $t->context ): ?>
		<span class="context bubble" title="<?php printf( __('Context: %s'), esc_html($t->context) ); ?>"><?php echo esc_html($t->context); ?></span>
		<?php endif; ?>
	
	</td>
	<td class="translation foreign-text">
	<?php
		$edit_text = $can_edit? __('Double-click to add') : sprintf(__('You <a href="%s">have to login</a> to add a translation.'), gp_url_login());
		$missing_text = "<span class='missing'>$edit_text</span>";
		if ( !count( array_filter( $t->translations ) ) ):
			echo $missing_text;
		elseif ( !$t->plural ):
			echo esc_translation( $t->translations[0] );
		else: ?>
		<ul>
			<?php
				foreach( $t->translations as $translation ):
			?>
				<li><?php echo $translation? esc_translation( $translation ) : $missing_text; ?></li>
			<?php
				endforeach;
			?>
		</ul>
	<?php
		endif;
	?>
	</td>
	<td class="actions">
		<a href="#" row="<?php echo $t->row_id; ?>" class="action edit bubble"><?php _e('Details'); ?></a>
	</td>
</tr>
<tr class="editor <?php echo $warning_class; ?>" id="editor-<?php echo $t->row_id; ?>" row="<?php echo $t->row_id; ?>">
	<td colspan="5">
		<div class="strings">
		<?php if ( !$t->plural ): ?>
		<p class="original"><?php echo prepare_original( esc_translation($t->singular) ); ?></p>
		<?php textareas( $t, array( $can_edit, $can_approve ) ); ?>
		<?php else: ?>
			<?php if ( $row_locale->nplurals == 2 && $row_locale->plural_expression == 'n != 1'): ?>
				<p><?php printf(__('Singular: %s'), '<span class="original">'.esc_translation($t->singular).'</span>'); ?></p>
				<?php textareas( $t, array( $can_edit, $can_approve ), 0 ); ?>
				<p class="clear">
					<?php printf(__('Plural: %s'), '<span class="original">'.esc_translation($t->plural).'</span>'); ?>
				</p>
				<?php textareas( $t, array( $can_edit, $can_approve ), 1 ); ?>
			<?php else: ?>
				<!--
				TODO: labels for each plural textarea and a sample number
				-->
				<p><?php printf(__('Singular: %s'), '<span class="original">'.esc_translation($t->singular).'</span>'); ?></p>
				<p class="clear">
					<?php printf(__('Plural: %s'), '<span class="original">'.esc_translation($t->plural).'</span>'); ?>
				</p>
				<?php foreach( range( 0, $row_locale->nplurals - 1 ) as $plural_index ): ?>
					<?php if ( $row_locale->nplurals > 1 ): ?>
					<p class="plural-numbers"><?php printf(__('This plural form is used for numbers like: %s'),
							'<span class="numbers">'.implode(', ', $row_locale->numbers_for_index( $plural_index ) ).'</span>' ); ?></p>
					<?php endif; ?>
					<?php textareas( $t, array( $can_edit, $can_approve ), $plural_index ); ?>
				<?php endforeach; ?>
			<?php endif; ?>
		<?php endif; ?>
		</div>
		
		<div class="meta">
			<h3><?php _e('Meta'); ?></h3>
			<dl>
				<dt><?php _e('Status:'); ?></dt>
				<dd>
					<?php echo display_status( $t->translation_status ); ?>
					<?php if ( $can_approve && $t->translation_status ): ?>
					
						<?php if ( $t->translation_status != 'current' ): ?>
						<button class="approve" tabindex="-1"><strong>+</strong> Approve</button>
						<?php endif; ?>
						<?php if ( $t->translation_status != 'rejected' ): ?>
						<button class="reject" tabindex="-1"><strong>&minus;</strong> Reject</button>
						<?php endif; ?>
					<?php endif; ?>
				</dd>
			</dl>
			<!--
			<dl>
				<dt><?php _e('Priority:'); ?></dt>
				<dd><?php echo esc_html($t->priority); ?></dd>
			</dl>
			-->
			
			<?php if ( $t->context ): ?>
			<dl>
				<dt><?php _e('Context:'); ?></dt>
				<dd><span class="context bubble"><?php echo esc_translation($t->context); ?></span></dd>
			</dl>
			<?php endif; ?>
			<?php if ( $t->extracted_comment ): ?>
			<dl>
				<dt><?php _e('Comment:'); ?></dt>
				<dd><?php echo make_clickable( esc_translation($t->extracted_comment) ); ?></dd>
			</dl>
			<?php endif; ?>
			<?php if ( $t->translation_added && $t->translation_added != '0000-00-00 00:00:00' ): ?>
			<dl>
				<dt><?php _e('Date added:'); ?></dt>
				<dd><?php echo $t->translation_added; ?> GMT</dd>
			</dl>
			<?php endif; ?>
            <dl>
                <dt><?php _e('In:'); ?></dt>
                <dd><?php echo $row_project->name; ?> / <?php echo $row_translation_set->name; ?></dd>
            </dl>
			<?php if ( $t->user_login ): ?>
			<dl>
				<dt><?php _e('Translated by:'); ?></dt>
				<dd><?php echo $row_user->display_name; ?></dd>
				&nbsp;&nbsp;&bull;&nbsp;&nbsp;
				<dt><?php _e('Username:'); ?></dt>
				<dd><?php echo $row_user->user_login; ?></dd>
			</dl>
			<?php endif; ?>
			<dl>
				<dt><?php _e('Translated into:'); ?></dt>
				<dd><?php echo $row_locale->english_name; ?></dd>
				&nbsp;&nbsp;&bull;&nbsp;&nbsp;
				<dt><?php _e('Code:'); ?></dt>
				<dd><?php echo $row_locale->slug; ?></dd>
			</dl>

			<?php references( $row_project, $t ); ?>
			
			<dl>
			    <dt><?php _e('Priority of the original:'); ?></dt>
			<?php if ( $can_write ): ?>
			    <dd><?php echo gp_select( 'priority-'.$t->original_id, GP::$original->get_static( 'priorities' ), $t->priority, array('class' => 'priority', 'tabindex' => '-1') ); ?></dd>
			<?php else: ?>
			    <dd><?php echo gp_array_get( GP::$original->get_static( 'priorities' ), $t->priority, 'unknown' ); ?></dd>
			<?php endif; ?>
			</dl>
			
			<?php $extra_args = $t->translation_status? array( 'filters[translation_id]' => $t->id ) : array(); ?>
			<dl>
<?php
		$permalink = gp_url_project_locale( $row_project, $row_locale->slug, $row_translation_set->slug,
        	array_merge( array('filters[status]' => 'either', 'filters[original_id]' => $t->original_id ), $extra_args ) );
		$original_history = gp_url_project_locale( $row_project, $row_locale->slug, $row_translation_set->slug,
        	array_merge( array('filters[status]' => 'either', 'filters[original_id]' => $t->original_id, 'sort[by]' => 'translation_date_added', 'sort[how]' => 'asc' ) ) );

?>
			    <dt>More links:
				<ul>
					<li><a tabindex="-1" href="<?php echo $permalink; ?>" title="Permanent link to this translation">Permalink to this translation</a></li>
					<li><a tabindex="-1" href="<?php echo $original_history; ?>" title="Link to the history of translations of this original">All translations of this original</a></li>
				</ul>
				</dt>
			</dl>
		</div>
		<div class="actions">
		<?php if ( $can_edit ): ?>
			<button class="btn btn-primary btn-small ok" data-url="<?php echo $editorUrl ?>">
				<?php echo $can_approve? __('Add translation &rarr;') : __('Suggest new translation &rarr;'); ?>
			</button>
		<?php endif; ?>
			or <a href="#" class="btn btn-small"><?php _e('Cancel'); ?></a>
		</div>
	</td>
</tr>