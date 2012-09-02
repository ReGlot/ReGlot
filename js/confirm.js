$('.delete').live('click', requiresConfirmation);

function requiresConfirmation(e) {
	var confirmText = $(this).data('message') || 'You really want to do this?';
	if ( !confirm(confirmText) ) {
		e.preventDefault();
	}
}
