jQuery(document).ready(function ($) {
	var debounceTimeout;

	$('#wtpc-height,#wtpc-width').on('keyup input blur paste', function (e) {
		$(this).closest('form').find('input[type="submit"]').click();
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

		$('#wtpc-width-input').val(!isNaN(width) ? width : '');
		$('#wtpc-height-input').val(!isNaN(height) ? height : '');
	}

	function formatArea(value) {
		return value.replace('.', ',') + ' m²';
	}

	function formatPrice(value) {
		return value.replace('.', ',') + '€';
	}

	calculatePrice();
});
