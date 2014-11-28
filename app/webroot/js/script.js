function bootstrap_hack(){
	// badge on click
	$('table .badge').bind('click', function(){
		window.location = $(this).find('a').attr('href');
	});
}

function init_sortable(){
	$('table.sortable tbody').sortable({ opacity: 0.6, cursor: 'move' });;
}

function init_popup(){
	$('.popup.url').colorbox();
	$('.popup.inline-url').colorbox();
}

$(document).ready(function(){
	var imgReady = $('body').imagesLoaded();
	imgReady.always(function(){
		// sidebar line
		var content_height = $('.content').height();
		var sidebar_height = $('.sidebar').height();
		
		if(sidebar_height < content_height){
			$('.sidebar').css('height', $('.content').css('height'));
		}
	});
	
	$('div#child-content').on("click", '#child-menu .btn-group .btn', function(){	
		$('#child-menu .btn').removeClass('active');
		$(this).addClass('active');
	});
	
	// list button hover
//	$('div#child-content').on("mouseenter", 'table tr', function(){	
//		$(this).find('td .btn').css('display', 'inline');
//	});
//	
//	$('div#child-content').on("mouseleave", 'table tr', function(){	
//		$(this).find('td .btn').css('display', 'none');
//	});
	
	// init sortable function
	init_sortable();
	
	// init popup function
	init_popup();
	
	// init bootstrap hack
	bootstrap_hack();
	// // copy tags function
	// $('div#child-content').on('click', '.copy-tag', function(e){
		// e.preventDefault();
		// // $.fn.copyToClipboard($(this).parents('div.photo').next('input[type=button]').attr('id') , 'wakakakak');
		// $(this).parents('div.photo').next('input[type=button]').click();
	// });
});

// on resize
$(window).resize(function(){
	// sidebar line
	var content_height = $('.content').height();
	var sidebar_height = $('.sidebar').height();
		
	if(sidebar_height < content_height){
		$('.sidebar').css('height', $('.content').css('height'));
	}
});