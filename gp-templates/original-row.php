<tr class="preview <?php echo $parity() ?>" id="preview-<?php echo $o->row_id ?>" row="<?php echo $o->row_id; ?>">
	<td class="original">
		<?php if ( $o->context ): ?>
		<span class="context bubble" title="<?php printf( __('Context: %s', 'glotpress'), esc_html($o->context) ); ?>"><?php echo esc_html($o->context); ?></span>
		<?php else: ?>
		No Context
		<?php endif; ?>
	</td>
	<td class="translation foreign-text">
		<?php echo prepare_original( esc_translation( $o->singular ) ); ?>
	</td>
	<td class="comment foreign-text">
		<?php echo prepare_original( esc_translation( $o->comment ) ); ?>
	</td>
</tr>