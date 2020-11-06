jQuery(document).ready(function ($) {
	$('html').on('click', '.wtpc_is_measurable', function () {
		if (this.checked) {
			$(this).closest('.options_group,.wtpc-variation-form').find('.show_if_measurable').show();
		} else {
			$(this).closest('.options_group,.wtpc-variation-form').find('.show_if_measurable').hide();
		}
	});
});
