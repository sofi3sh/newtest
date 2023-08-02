/* global woodmart_settings */
(function($) {
	woodmartThemeModule.countProductVisits = function () {
		var live_duration = 10000;

		if ( 'undefined' !== typeof woodmart_settings.counter_visitor_live_duration ) {
			live_duration = woodmart_settings.counter_visitor_live_duration;
		}

		if ('yes' === woodmart_settings.counter_visitor_ajax_update) {
			woodmartThemeModule.updateCountProductVisits();
		} else {
			setTimeout(function() {
				woodmartThemeModule.updateCountProductVisits();
			}, live_duration);
		}
	}

	woodmartThemeModule.updateCountProductVisits = function() {
		$('.wd-visits-count').each( function () {
			var $this = $(this);
			var productId = $this.data('product-id');
			var count = $this.find('.wd-visits-count-number').text();

			if ( ! productId ) {
				return;
			}

			$.ajax({
				url     : woodmart_settings.ajaxurl,
				data    : {
					action    : 'woodmart_update_count_product_visits',
					product_id: productId,
					count     : count,
				},
				method  : 'POST',
				success : function(response) {
					if (response) {
						$this.find('.wd-visits-count-number').html(response.count);

						if (!response.count) {
							$this.addClass('wd-hide');
						} else {
							$this.removeClass('wd-hide');
						}

						if ('yes' === response.live_mode) {
							setTimeout(function() { woodmartThemeModule.countProductVisits() }, woodmart_settings.counter_visitor_live_duration)
						}
					}
				},
				error   : function() {
					console.log('ajax error');
				},
				complete: function() { }
			});
		});
	};

	$(document).ready(function() {
		woodmartThemeModule.countProductVisits();
	});
})(jQuery);
