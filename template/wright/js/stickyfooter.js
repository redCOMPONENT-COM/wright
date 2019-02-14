jQuery(document).ready(function($) {
	function stickyFooter() {
		if ($('#footer')) {
			var h = $('#footer').outerHeight();
			$('.wrapper-footer').height(h);
		}
	}
	$(window).load(function() {
		$('#footer.sticky').css('bottom', '0')
			.css('position', 'absolute')
			.css('z-index', '1000');
		stickyFooter();
	});
	stickyFooter();
	$(window).resize(function() {
		stickyFooter();
	});
});