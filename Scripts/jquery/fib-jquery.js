/* Quentin T + Erik - http://toki-woki.net - Originally coded for Boks (http://toki-woki.net/p/Boks/) */
function fixImageBaselines(selector) {
	$(document).ready(function() {
		var sbl = $.browser.msie ? document.body.currentStyle.lineHeight : $(document.body).css('line-height');
		var bl = parseInt(sbl);
		if (sbl.indexOf('pt') != -1) bl = parseFloat(sbl) * (20 / 14.94); // IE!
		$(selector).each(function() {
			var $item = $(this);
			var $d = $('<div />');
			$d.css({
				'padding-bottom': (bl * (Math.ceil($item.height() / bl)) - $item.height()) + 'px',
				'background': 'transparent url(' + $item.attr('src') + ') no-repeat 0 0',
				'width': $item.width(),
				'height': $item.height()
			});
			$d.id=$item.id;
			$d.className=$item.className;
			$item.replaceWith($d);
		});
	});
}