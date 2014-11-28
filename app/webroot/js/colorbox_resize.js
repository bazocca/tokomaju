// =========================== >>
/* Colorbox resize function */
// =========================== >>
(function($){
	var resizeTimerColorbox;
	function resizeColorBox()
	{
	    if (resizeTimerColorbox) clearTimeout(resizeTimerColorbox);
	    resizeTimerColorbox = setTimeout(function() {
	        if ($('#cboxOverlay').is(':visible')) {
	            $.colorbox.load(true);
	        }
	    }, 300)
	}

	// Resize Colorbox when resizing window or changing mobile device orientation
	$(window).resize(resizeColorBox);
	window.addEventListener("orientationchange", resizeColorBox, false);

	/*	
	Use this method with jQuery colorbox initialization as follow :

	jQuery(*selector*).colorbox({maxWidth:'95%', maxHeight:'95%'});
	*/
})(jQuery);