(function($) {
	"use strict";
	$('.slide').on('slid.bs.carousel', function () {
		$(this).find('.carousel-title-indicators').find('.active').removeClass('active');
		var index = $(this).find('.carousel-indicators').find('.active').data('slide-to');
		$(this).find('.carousel-title-indicators li[data-slide-to=' + index + ']').addClass('active');
	});
})(jQuery);
