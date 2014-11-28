/* basic */
$(document).ready(function() {
	var now = new Date();
//	console.log(now.getFullYear());
	$('.year-footer').html(now.getFullYear());
});

/* End Basic */

/* Dropdown menu init data */
/***********************************************
* AnyLink JS Drop Down Menu v2.0- © Dynamic Drive DHTML code library (www.dynamicdrive.com)
* This notice MUST stay intact for legal use
* Visit Project Page at http://www.dynamicdrive.com/dynamicindex1/dropmenuindex.htm for full source code
***********************************************/

anylinkmenu.init("menuanchorclass");

var anylinkmenu1={divclass:'anylinkmenu', inlinestyle:'', linktarget:''} 
anylinkmenu1.items=[
	["FALL/WINTER 2011", "#"],	//format: ["label","link"]
	["SPRING/SUMMER 2012", "#"], //ex: change "#" to valid link "http://google.com"
	["FALL/WINTER 2012", "#"]
];

$(document).ready(function() {
	//select menu function
	$(document).delegate('.anylinkmenu a',"click", function(event) { 
		$('#menuTrend').html($(this).html());
		
		$("#collection").val($(this).html());
		
		var formId='#DbentriesListChangecollectionForm';
        //$(formId).html($(this).html());
        var action=$(formId).attr('action');
        var myData=$(formId).serialize();
		
		$.post(action,myData,
			function(data){
			
				$('#SliderName_1').html(data);
				
				//effectsDemo1 = 'fade';
				demoSlider_1.stop();
				demoSlider_1 = Sliderman.slider({container: 'SliderName_1', width: 680, height: 475, effects: effectsDemo1,
						display: {
						autoplay: 3000,
							loading: {background: '#000000', opacity: 0.5, image: 'img/icon/loading.gif'},
							buttons: {hide: false, opacity: 1, prev: {className: 'SliderNamePrev_1', label: ''}, next: {className: 'SliderNameNext_1', label: ''}}
						}
					});
				demoSlider_1.start();
				demoSlider_1.pause();
		});
		
		event.preventDefault();
		
	});
	
	$('.menuanchorclass').click(
		function () {
				$(this).parent().siblings().find("ul").slideUp("normal"); // Slide up all sub menus except the one clicked
				$(this).next().slideToggle("normal"); // Slide down the clicked sub menu
				return false;
			}
	);
});
/*****************************************************************************************/


/* Accordion init data */
$(document).ready(function() {
	$("#slider").liteAccordion({
		theme  			: "dark",
		easing 			: "easeOutExpo",
		containerHeight : 500,
		containerWidth	: 893,
		linkable 		: true,
		firstSlide : 1,
		headerWidth		: 35
	});
	
	//remove if not use
	$('#slider ol li').click(function(){
//		console.log($(this).html());   
	});
	/////////////////////////////////////////
	
});
	
function toggle(idnum) {
	if (idnum == 1)
	{
		document.getElementById("home").style.display = "block";
		document.getElementById("about").style.display = "none";
		document.getElementById("press").style.display = "none";
		document.getElementById("collaborations").style.display = "none";
		document.getElementById("retailers").style.display = "none";
		document.getElementById("events").style.display = "none";
	}
	else if (idnum == 2)
	{
		document.getElementById("home").style.display = "none";
		document.getElementById("about").style.display = "block";
		document.getElementById("press").style.display = "none";
		document.getElementById("collaborations").style.display = "none";
		document.getElementById("retailers").style.display = "none";
		document.getElementById("events").style.display = "none";
	}
	else if (idnum == 3)
	{
		document.getElementById("home").style.display = "none";
		document.getElementById("about").style.display = "none";
		document.getElementById("press").style.display = "block";
		document.getElementById("collaborations").style.display = "none";
		document.getElementById("retailers").style.display = "none";
		document.getElementById("events").style.display = "none";
	}
	else if (idnum == 4)
	{
		document.getElementById("home").style.display = "none";
		document.getElementById("about").style.display = "none";
		document.getElementById("press").style.display = "none";
		document.getElementById("collaborations").style.display = "block";
		document.getElementById("retailers").style.display = "none";
		document.getElementById("events").style.display = "none";
	}
	else if (idnum == 5)
	{
		document.getElementById("home").style.display = "none";
		document.getElementById("about").style.display = "none";
		document.getElementById("press").style.display = "none";
		document.getElementById("collaborations").style.display = "none";
		document.getElementById("retailers").style.display = "block";
		document.getElementById("events").style.display = "none";
	}
	else if (idnum == 6)
	{
		document.getElementById("home").style.display = "none";
		document.getElementById("about").style.display = "none";
		document.getElementById("press").style.display = "none";
		document.getElementById("collaborations").style.display = "none";
		document.getElementById("retailers").style.display = "none";
		document.getElementById("events").style.display = "block";
	}
} 
/*****************************************************************************************/


/* Sliderman init data */
var demoSlider_1;
var demoSlider_2;
$(document).ready(function() {
	
	effectsDemo1 = 'fade';
	demoSlider_1 = Sliderman.slider({container: 'SliderName_1', width: 680, height: 475, effects: effectsDemo1,
		display: {
			autoplay: 3000,
			loading: {background: '#000000', opacity: 0.5, image: 'img/icon/loading.gif'},
			buttons: {hide: false, opacity: 1, prev: {className: 'SliderNamePrev_1', label: ''}, next: {className: 'SliderNameNext_1', label: ''}}
		}
	});
	
	demoSlider_1.start();
	demoSlider_1.pause();
	
	effectsDemo2 = 'fade';
	demoSlider_2 = Sliderman.slider({container: 'SliderName_2', width: 680, height: 475, effects: effectsDemo2,
		display: {
			autoplay: 3000,
			autostart: false,
			loading: {background: '#000000', opacity: 0.5, image: 'img/icon/loading.gif'},
			buttons: {hide: false, opacity: 1, prev: {className: 'SliderNamePrev_2', label: ''}, next: {className: 'SliderNameNext_2', label: ''}}
		}
	});
	
	//enable and disable current slide. Only one slide will be played at the time.
	$('#slider ol li h2.look').click(function(){
		demoSlider_2.stop();
		demoSlider_1.start();
		demoSlider_1.pause();
	});
	$('#slider ol li h2.press').click(function(){
		demoSlider_1.stop();
		demoSlider_2.start();
		demoSlider_2.pause();
	});
});