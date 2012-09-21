jQuery(function($) {
	$gp.showhide('a.revealing.projects', 'form.filters-toolbar div.projects', {
		show_text: 'Projects &darr;',
		hide_text: 'Projects &uarr;', 
		focus: '#sort\\[by\\]'
	});
	$gp.showhide('a.revealing.locales', 'form.filters-toolbar div.locales', {
		show_text: 'Locales &darr;',
		hide_text: 'Locales &uarr;',
		focus: '#filters\\[term\\]'
	});
	$('#add_project').click(function() {
		return !$('#select1_project option:selected').remove().appendTo('#select2_project');
	});
	$('#remove_project').click(function() {
		return !$('#select2_project option:selected').remove().appendTo('#select1_project');
	});
	$('#add-all_project').click(function() {
		return !$('#select1_project option').remove().appendTo('#select2_project');
	});
	$('#remove-all_project').click(function() {
		return !$('#select2_project option').remove().appendTo('#select1_project');
	});
	$('#add_locale').click(function() {
		return !$('#select1_locale option:selected').remove().appendTo('#select2_locale');
	});
	$('#remove_locale').click(function() {
		return !$('#select2_locale option:selected').remove().appendTo('#select1_locale');
	});
	$('#add-all_locale').click(function() {
		return !$('#select1_locale option').remove().appendTo('#select2_locale');
	});
	$('#remove-all_locale').click(function() {
		return !$('#select2_locale option').remove().appendTo('#select1_locale');
	});
	$('form.filters-toolbar').submit(function() {
		hidden = $('input[name="export[project_selection]"]', this);
		hidden.val('');
		if ( $('#select1_project option').length > 0 && $('#select2_project option').length > 0 ) {
			theval = $('#select2_project option').each(function(index, option) {
				if ( hidden.val() ) {
					hidden.val(hidden.val() + "|" + option.value);
				} else {
					hidden.val(option.value);
				}
			});
		}
		hidden = $('input[name="export[locale_selection]"]', this);
		hidden.val('');
		if ( $('#select1_locale option').length > 0 && $('#select2_locale option').length > 0 ) {
			theval = $('#select2_locale option').each(function(index, option) {
				if ( hidden.val() ) {
					hidden.val(hidden.val() + "|" + option.value);
				} else {
					hidden.val(option.value);
				}
			});
		}
	});
});