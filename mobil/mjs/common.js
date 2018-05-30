$(function () {
	var logoHoverEnabled = true;
	$('.navbar-brand').hover(function () {
		if (logoHoverEnabled) {
			$('.logo-red').css('opacity', '1');
		}
	}, function () {
		if (logoHoverEnabled) {
			$('.logo-red').css('opacity', '0');
		}
	});
	$('.navbar-collapse#navbar-main').on('show.bs.collapse', function () {
		$('.logo-red').css('opacity', '1');
		logoHoverEnabled = false;
	});
	$('.navbar-collapse#navbar-main').on('hide.bs.collapse', function () {
		$('.logo-red').css('opacity', '0');
		logoHoverEnabled = true;
	});
	$('.vacancy-form a[data-toggle="tab"], .vacancy-form-cashier a[data-toggle="tab"], .vacancy-form-guardian a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
		$('.vacancy-form .vacancy-info-navigation a, .vacancy-form-cashier .vacancy-info-navigation a, .vacancy-form-guardian .vacancy-info-navigation a').removeClass('active');
		$(this).addClass('active');
	});
	$('#saymail').on('show.bs.modal', function (event) {
		var button = $(event.relatedTarget);
		var modal = $(this);
		var evaluation = button.data('evaluation');
		modal.find('input:radio#comment-radio-' + evaluation).prop('checked', true);
	})
	$('#saymail').on('shown.bs.modal', function (event) {
		$('.evaluate-us > a').one('focus', function (event) {
			$(this).blur();
		});
	});
	$('form.disabled-while-empty input').on('change keyup', function () {
		var form = $(this).closest('form');
		var formCompletelyFilled = true;
		form.find('input[required]').each(function () {
			if ($(this).val() == '') {
				formCompletelyFilled = false;
			}
		});
		if (formCompletelyFilled) {
			$(form).find('.modal-footer button[type=submit]').removeAttr('disabled');
		} else {
			$(form).find('.modal-footer button[type=submit]').attr('disabled', 'disabled');
		}
	});
});
