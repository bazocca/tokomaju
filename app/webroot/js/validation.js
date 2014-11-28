// ------------------------------------------------------------- //
// list of allowed validations per field type !!
// ------------------------------------------------------------- //
var allowed_validations = new Array();
var this_element = '';
function string_unslug(str)
{
	var temp = str.split('_');
	for(key in temp)
	{
		temp[key] = temp[key].substr(0,1).toUpperCase() + temp[key].substr(1);
	}
	return temp.join(" ");
}

function get_input_attrib(src , value)
{
	src = src.toUpperCase();
	value = value.toUpperCase();
	
	var temp = src.indexOf(value);
	var result = "";
	if(temp >= 0)
	{
		src = src.substr(temp); //  ???|??|MIN_LENGTH_5|???|??  => MIN_LENGTH_5|???|???
		var pos = src.indexOf('|');
		src = (pos<0?src:src.substr(0,pos));
		result = src.substr( src.lastIndexOf('_') + 1 );
	}
	return result;
}

function validation_check(field){
	// hide all validations & options field
	$('.form-popup .validation .control-group').css('display', 'none');
	$('.form-popup .validation .control-group .controls input[type=checkbox]').val('no');
	$('.form-popup .options').css('display', 'none');

	// set validation type conditions
	switch(field){
		case 'color':
		case 'datepicker':
		case 'datetimepicker':
		case 'timepicker':
		case 'image':
		case 'gallery':
		case 'radio':
		case 'dropdown':
		case 'file':
		case 'browse':
		case 'multibrowse':
			allowed_validations = ['not_empty'];
			break;
		case 'checkbox':
			allowed_validations = ['not_empty' , 'browse_module'];
			break;	
		case 'password':
			allowed_validations = ['is_alnum','is_numeric', 'not_empty', 'min_length', 'max_length'];
			break;
		case '':
			break;
		default:
			allowed_validations = ['is_url','is_alnum','is_numeric', 'not_empty', 'is_email', 'min_length', 'max_length'];
			break;
	}
	
	// set conditions for field options
	switch(field){
		case 'radio':
		case 'dropdown':
		case 'checkbox':
			$('.form-popup .options').slideDown('fast', function(){
				$.colorbox.resize();				
				$('input[type=hidden]#typeMetaValueState').val('exist');
			});
			break;
		default: 
			$('.form-popup .options').slideUp('fast');
			$('input[type=hidden]#typeMetaValueState').val('');
			break;
	}
	
	n = allowed_validations.length;
	for(var a=0;a<n;a++){
		$('.validation .'+allowed_validations[a]).css('display', 'block');
		$('.validation .'+allowed_validations[a]+' .controls input[type=checkbox]').val('yes');
	}
}
(function($){
	$(document).ready(function()
	{
		($('#colorbox').length>0&&$('#colorbox').is(':visible')?$('#colorbox').children().last().children():$(document)).on("keypress","form#TypeFormPopupForm",function(){
			$('div#form-error').slideUp('fast');
		});
		($('#colorbox').length>0&&$('#colorbox').is(':visible')?$('#colorbox').children().last().children():$(document)).on("submit",'form#TypeFormPopupForm',function(e){
			e.preventDefault();	
			var action=$(this).attr('action');
			var myData=$(this).serialize();
			$.ajaxSetup({cache: false});
			$.post(action,myData,function(data){
				if(data.state == 'failed')
				{	
					$('div#form-error').html('Invalid input.');
					$('div#form-error').slideDown('fast', function(){
						$.colorbox.resize();	
					});
				}
				else if(data.state == 'minmax')
				{
					$('div#form-error').html('Min length must be smaller than or equal to max length.');
					$('div#form-error').slideDown('fast', function(){
						$.colorbox.resize();	
					});
				}
				else
				{
					if(this_element == '')
					{
						var content = '<tr class="input_list">';
						content += '<td><h5>'+data.frontKey+'</h5></td>';
						content += '<td>'+data.input_type+'</td>';
						content += '<td>';
						content += '<div class="btn-group" style="float:left;">';
						content += '<a class="btn btn-mini" href="#"><i class="icon-cog"></i></a><a class="btn btn-mini dropdown-toggle" data-toggle="dropdown" href="#"><span class="caret"></span>&nbsp;</a>';
						content += '<ul class="dropdown-menu">';
						content += '<li><a alt="fresh" class="popup url edit-field" href="'+linkpath+'types/form_popup/edit"><i class="icon-pencil"></i> Edit</a></li>';
						content += '<li><a class="delete-field" href="javascript:void(0)"><i class="icon-trash"></i> Delete</a></li>';
						content += '</ul>';
						content += '</div>';
						content += '<input type="hidden" name="data[TypeMeta][][key]" value="'+data.key+'"/>';
						content += '<input type="hidden" name="data[TypeMeta][][value]" value="'+data.value+'"/>';
						content += '<input type="hidden" name="data[TypeMeta][][input_type]" value="'+data.input_type+'"/>';
						content += '<input type="hidden" name="data[TypeMeta][][validation]" value="'+data.validation+'"/>';
						content += '<input type="hidden" name="data[TypeMeta][][instruction]" value="'+data.instruction+'"/>';
						content += '</td>';
						content += '</tr>';
						$('tbody#myInputWrapper').append(content);
					}
					else
					{	
						this_element.eq(1).val(data.value);
						this_element.eq(3).val(data.validation);
						this_element.eq(4).val(data.instruction);
					}
					$.colorbox.close();
				}
			},'json');
		});

		($('#colorbox').length>0&&$('#colorbox').is(':visible')?$('#colorbox').children().last().children():$(document)).on("keyup","input[type=text].input_attribute",function(e){
			if($.trim($(this).val()) == "")
			{			
				$(this).parent().children('input[type=checkbox].input_attribute').attr('checked' , false);
			}
			else
			{
				$(this).parent().children('input[type=checkbox].input_attribute').attr('checked' , true);
			}
		});

		($('#colorbox').length>0&&$('#colorbox').is(':visible')?$('#colorbox').children().last().children():$(document)).on("change",'input[type=checkbox].input_attribute',function(e){
			if(!$(this).is(':checked'))
			{				
				$(this).parent().children('input[type=text].input_attribute').val('');
			}
		});
		
		($('#colorbox').length>0&&$('#colorbox').is(':visible')?$('#colorbox').children().last().children():$(document)).on("click",'table a.popup.url.edit-field',function(e){
			e.preventDefault();
			this_element = $(this).parents('tr.input_list').find('input[type=hidden]');
			if($(this).attr('alt') == 'fresh')
			{
				$(this).attr('alt' , 'old');
				$(this).colorbox({open:true});
			}
		});
	});
})(jQuery);