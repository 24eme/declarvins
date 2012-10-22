(function($)
{
	$(document).ready( function() {
       $('body').konami(function () {
    	   $('#konami').show();
    	   $('body').css('background-color', '#C776AE');
    	   $("#konami img").animate({left:"+=2200px"},8000, function() {$('#konami').hide(); $('body').css('background-color', '#989898');}).animate({left:"-200px"}, 0);
       });
	});
	$.fn.konami = function(callback) {
		var code = "38,38,40,40,37,39,37,39,66,65";
		$(this).each(function() {
			var kkeys = [];
			$(this).keydown(function(e) {
				kkeys.push(e.keyCode);
				if(kkeys.toString().indexOf(code) >= 0) {
					kkeys = [];
					callback();
				}
			});
		});
	};
})(jQuery);