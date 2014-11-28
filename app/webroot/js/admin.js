var jcrop_api = new Array();
(function($){
	$(document).ready(function()
	{
		// colorbox initialization !!
		if($('.cboxElement').length > 0)
		{
			$('.cboxElement').colorbox({
		        fixed: true,
		        reposition: false,
		        maxWidth:'95%',
		        maxHeight:'95%'
		    });
		}
		
		// disable right-click for image !!
		$('img').bind('contextmenu', function(e) {
			return false;
		}); 

	    //trigger for all form to show dialog box when user close windows but data didn't(forgot) save
		($('#colorbox').length>0&&$('#colorbox').is(':visible')?$('#colorbox').children().last().children():$(document)).on("change",'form.notif-change',function(e){
	        window.onbeforeunload=function()
			{
	             return 'You have unsaved changes. Are you sure you want to leave this page?';    
	        };
	    });
	    
		($('#colorbox').length>0&&$('#colorbox').is(':visible')?$('#colorbox').children().last().children():$(document)).on("submit",'form.notif-change',function(e){
	    	window.onbeforeunload=function(){};
	    });
	    
	    $("a.removeID").click(function(){
	    	$(this).parents("div.control-group").find("input.targetID").val("");
	    	$(this).parents("div.control-group").find("input.targetID").change();	    	
	    	// $(this).parents("div.control-group").find("input.targetID").nextAll("p.help-block").html("");	    	
	    	$(this).parents("div.control-group").find("input[type=hidden]").val("");
	    });
	    
	    // AJAX IN EDIT FORM (CHANGE LANGUAGE)
		$('div#child-content').on("click", '.ajax_myform', function(e){
			e.preventDefault();
			var myobj = $(this);
			var myid = 'ajaxed';
			var url = myobj.attr('href');
			
			if(url == "#")
			{
				// just change now language
				var now_language = $(this).html().substr(0,2);
				$('a#lang_identifier').html( now_language );
				$('input[type=hidden]#myLanguage').val( now_language.toLowerCase() );
			}
			else
			{
				var spinner = '<div class="loading" style="height:'+$('#'+myid).height()+'px;"></div>';
				$.ajaxSetup({cache: false});
				$('div#'+myid).empty();
				$('div#'+myid).html(spinner).load(url,[],function(){
					history.pushState(null, '', url);
					// get hidden data
					var now_language = $('input[type=hidden]#myLanguage').val().toUpperCase();
					var entry_title = $('input[type=text].Title').val();
					var entry_image_id = $('input[type=hidden]#mySelectCoverId').val();
					var entry_image_type = $('input[type=hidden]#entry_image_type').val();
					
					// change now language
					$('a#lang_identifier').html( now_language );
					
					// change form title
					$('h2#form-title-entry').html(url.indexOf('lang=') >= 0? 'TRANSLATE ('+entry_title+')' : entry_title);
					// refresh ckeditor...
					$.fn.refresh_ckeditor();
					// refresh cover image...
					$('img#mySelectCoverAlbum').attr('src',site+'img/upload/thumb/'+entry_image_id+'.'+entry_image_type);
					if(entry_image_id == 0)
					{	
						$('.select').html('Select Cover');
						$('.remove').hide();
					}
					else
					{
						$('.select').html('Change Cover');
						$('.remove').show();
					}
					// refresh colorbox image link !!
					$('.get-from-library').colorbox({close: '<div class="icon-remove icon-white"></div>'});
				});
			}
		});
		
		// ------------------------ JCROP FUNCTION ------------------------ //
		$.fn.jCropSetSelectCoord = function(counter){
			var x1 = $('input[type=text]#x1_'+counter).val();
			var y1 = $('input[type=text]#y1_'+counter).val();
			var x2 = $('input[type=text]#x2_'+counter).val();
			var y2 = $('input[type=text]#y2_'+counter).val();
			jcrop_api[counter].setSelect([x1 , y1 , x2 , y2]);
		}
		
		$.fn.jCropSetSelectSize = function(counter){
			var x1 = $('input[type=text]#x1_'+counter).val();
			var y1 = $('input[type=text]#y1_'+counter).val();
			var x2 = parseInt($('input[type=text]#x1_'+counter).val()) + parseInt($('input[type=text]#w_'+counter).val());
			var y2 = parseInt($('input[type=text]#y1_'+counter).val()) + parseInt($('input[type=text]#h_'+counter).val());
			jcrop_api[counter].setSelect([x1 , y1 , x2 , y2]);
		}
		// -------------------- END OF JCROP FUNCTION --------------------- //
	});
})(jQuery);