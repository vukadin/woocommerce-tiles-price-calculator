jQuery(document).ready(function ($) {
	var debounceTimeout;

	$('#wtpc-height,#wtpc-width').on('keyup input paste', function (e) {
		clearTimeout(debounceTimeout);
		debounceTimeout = setTimeout(function () {
			calculatePrice();
		}, 50);
	});

	$('#wtpc-dimensions-calculator form').on('submit', function (e) {
		e.preventDefault();
	});

	function calculatePrice() {
		var height = parseInt($('#wtpc-height').val()),
			width = parseInt($('#wtpc-width').val()),
			area = 0,
			price_per_sqm = Number($('#wtpc-dimensions-calculator').data('price')),
			total_price = '';

		if (!isNaN(width) && !isNaN(height) && width > 0 && height > 0) {
			area = ((width * height) / 1000000).toFixed(3);
			total_price = (price_per_sqm * area).toFixed(2);
		}

		$('#wtpc-calculated-area').html(formatArea(area));
		$('#wtpc-calculated-price').html(total_price ? formatPrice(total_price) : '');
	}

	function formatArea(value) {
		return (
			String(value)
				.replace(/\.?0+$/, '')
				.replace('.', ',') + ' m²'
		);
	}

	function formatPrice(value) {
		return String(value).replace('.', ',') + '€';
	}

	$('.variations_form').on('found_variation', function (e, variation) {
		if (!variation['wtpc_is_measurable']) return;

		$('#wtpc-width').attr({
			min: variation['wtpc_min_width'],
			max: variation['wtpc_max_width'],
			required: 'required',
		});

		$('#wtpc-height').attr({
			min: variation['wtpc_min_height'],
			max: variation['wtpc_max_height'],
			required: 'required',
		});

		$('#wtpc-min-width-label').text(variation['wtpc_min_width']);
		$('#wtpc-max-width-label').text(variation['wtpc_max_width']);
		$('#wtpc-min-height-label').text(variation['wtpc_min_height']);
		$('#wtpc-max-height-label').text(variation['wtpc_max_height']);

		calculatePrice();

		$('#wtpc-dimensions-calculator').data('price', variation['display_price']).show();
	});
	$('.variations_form').on('check_variations', function () {
		$('#wtpc-dimensions-calculator').hide().find('input[required]').removeAttr('required').val('');
	});

	calculatePrice();
});
