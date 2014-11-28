<?php
	$_SESSION['now'] = str_replace('&amp;','&',htmlentities($_SERVER['REQUEST_URI']));
	$this->Html->addCrumb('Settings', '/admin/settings');
?>
<script type="text/javascript">
	$(document).ready(function(){
		$("a#settings").addClass("active");
		
		$.fn.add_new_lang = function(src){
			var content = '<div class="control-group group-checkbox">';
			content += '<input type="hidden" value="'+src+'">';
			content += '<div class="controls inline">';
			content += '<input class="normal-checkbox" type="checkbox" name="data[Setting][15][optlang]['+src+']" value="nofixed" CHECKED />';
			content += '<label class="control-label label-checkbox">'+lang_unslug(src)+'</label>';
			content += '</div>';
			content += '</div>';
			return content;
		}
		
		$.fn.validate_lang = function(mycode , mylang){
			// FIRST CHECK !!
			if(mycode.length <= 0 || !isNaN(mycode) || mylang.length <= 0 || !isNaN(mylang))
			{
				return false;
			}
			// SECOND CHECK !!
			var state = 1;
			$("div#optlang div.group-checkbox").each(function(){
				var temp = $(this).find('input[type=hidden]').val().split('_');
				if($.trim(temp[0]).toLowerCase() == mycode.toLowerCase() || $.trim(temp[1]).toLowerCase() == mylang.toLowerCase())
				{	
					state = 0;
					return;
				}
			});
			return (state==1?true:false);
		}		
		
		$("input[type=checkbox]#multilanguage").change(function(){
			if($(this).is(':checked'))
			{	
				$("div#optlang").slideDown('fast',function(){
					$("a.cancel_lang").click();
				});
			}
			else
			{
				$("div#optlang").slideUp('fast');
			}
		});
		$("select#default_language").change(function(){
			var value = $(this).val();
			$("div#optlang div.group-checkbox input[type=checkbox]").attr('disabled' , false);
			$("div#optlang div.group-checkbox").each(function(){
				if($(this).find('input[type=hidden]').val() == value)
				{
					$(this).find('input[type=checkbox]').attr('disabled' , true);
					$(this).find('input[type=checkbox]').attr('checked' , true);
					return;
				}
			});
		});
		$('div#optlang div.group-checkbox input[type=checkbox][value=nofixed]').on("change",function(){
			if(!$(this).is(':checked'))
			{	
				$(this).parents('div.group-checkbox').animate({opacity : 0 , height : 0, marginBottom : 0},1000,function(){
					$("select#default_language").find("option[value="+$(this).find('input[type=hidden]').val()+"]").detach();
					$(this).detach();
				});
			}
		});
		$("a.add_lang").click(function(){
			if($("div#lang_interaction a.cancel_lang").css('display') == 'none')
			{
				$("div#lang_interaction").find('input[type=text]').show();
				$("div#lang_interaction a.add_lang").html('Save');
				$("div#lang_interaction a.cancel_lang").show();
			}
			else
			{
				var mycode = $.fn.slug($.trim($("div#lang_interaction").find('input[type=text]').eq(0).val()));
				var mylang = $.fn.slug($.trim($("div#lang_interaction").find('input[type=text]').eq(1).val()));
				if($.fn.validate_lang(mycode , mylang))
				{
					var result = mycode.toLowerCase()+'_'+mylang.toLowerCase();
					$("select#default_language").append("<option value='"+result+"'>"+lang_unslug(result)+"</option>");
					$("div#optlang div.container-box.small").append($.fn.add_new_lang(result));
				}
				else
				{
					alert('Invalid Language! Please try again..');
					return;
				}
				$("a.cancel_lang").click();
			}
		});
		$("a.cancel_lang").click(function(){
			$("div#lang_interaction").find('input[type=text]').hide();
			$("div#lang_interaction").find('input[type=text]').val('');
			$("div#lang_interaction a.add_lang").html('Add More Language...');
			$("div#lang_interaction a.cancel_lang").hide();
		});
	});
</script>
<div class="inner-header">
	<div class="title">
		<h2>SETTINGS</h2>
		<p class="title-description">All kinds of web settings.</p>
	</div>
</div>
	
<div class="inner-content">		
<?php
	echo $this->Form->create('Setting', array('action'=>'index','type'=>'file','class'=>'notif-change form-horizontal fl','inputDefaults' => array('label' =>false , 'div' => false)));
?>
	<fieldset>
		<p class="notes important" style="color: red;font-weight: bold;">* Red input MUST NOT be empty.</p>
<!-- 		Basic Setting -->
		<div class="alert alert-info full fl">
			<strong>Basic Setting</strong>
		</div>		
		<?php
			// Title...
			$value['counter'] = 0;
			$value['key'] = 'form-'.string_unslug($mySetting[$value['counter']]['Setting']['key']);
			$value['validation'] = 'not_empty';
			$value['value'] = $mySetting[$value['counter']]['Setting']['value'];
			$value['model'] = 'Setting';
			$value['input_type'] = 'text';
			echo $this->element('input_'.$value['input_type'] , $value);
			
			// Tagline...
			$value['counter'] = 1;
			$value['key'] = 'form-'.string_unslug($mySetting[$value['counter']]['Setting']['key']);
			$value['validation'] = '';
			$value['value'] = $mySetting[$value['counter']]['Setting']['value'];
			$value['model'] = 'Setting';			
			$value['input_type'] = 'text';
			echo $this->element('input_'.$value['input_type'] , $value);
			
			// Description...
			$value['counter'] = 2;
			$value['key'] = 'form-'.string_unslug($mySetting[$value['counter']]['Setting']['key']);
			$value['validation'] = '';
			$value['value'] = $mySetting[$value['counter']]['Setting']['value'];
			$value['model'] = 'Setting';			
			$value['input_type'] = 'textarea';
			$value['p'] = 'About 150 words recommended';
			echo $this->element('input_'.$value['input_type'] , $value);
			unset($value['p']);
			
			// Date Format...
			$value['counter'] = 3;
			$value['key'] = 'form-'.string_unslug($mySetting[$value['counter']]['Setting']['key']);
			$value['validation'] = 'not_empty';
			$value['value'] = $mySetting[$value['counter']]['Setting']['value'];
			$value['model'] = 'Setting';			
			$value['input_type'] = 'dropdown';
			$value['list'][0]['id'] = 'Y-m-d';
			$value['list'][1]['id'] = 'd-m-Y';
			$value['list'][2]['id'] = 'm-d-Y';
			$value['list'][3]['id'] = 'Y F d';
			$value['list'][4]['id'] = 'd F Y';
			$value['list'][5]['id'] = 'F d, Y';
			for ($i=0; $i <= 5 ; $i++) $value['list'][$i]['name'] = date($value['list'][$i]['id'] , gmt_adjustment());
			echo $this->element('input_'.$value['input_type'] , $value);
			unset($value['list']);
			
			// Time Format...
			$value['counter'] = 4;
			$value['key'] = 'form-'.string_unslug($mySetting[$value['counter']]['Setting']['key']);
			$value['validation'] = 'not_empty';
			$value['value'] = $mySetting[$value['counter']]['Setting']['value'];
			$value['model'] = 'Setting';
			$value['input_type'] = 'dropdown';
			$value['list'][0]['id'] = 'H:i:s';
			$value['list'][1]['id'] = 'H:i';
			$value['list'][2]['id'] = 'h:i:s A';
			$value['list'][3]['id'] = 'h:i A';
			for ($i=0; $i <= 3 ; $i++) $value['list'][$i]['name'] = date($value['list'][$i]['id'] , gmt_adjustment());
			echo $this->element('input_'.$value['input_type'] , $value);
			unset($value['list']);
		?>		
<!-- 		LANGUAGE SETTINGS -->		
		<div class="control-group" style="margin-bottom: 18px;">            
			<label style="color: red;" class="control-label">Default Language</label>
			<div class="controls inline">
				<select id="default_language" name="data[Setting][15][value]" class="field_type">
					<?php
						$langlist = get_list_lang($mySetting[15]['Setting']['value']);
						foreach ($langlist as $key10 => $value10)
						{
							if(strtolower(substr($mySetting[15]['Setting']['value'], 0 , 2))==strtolower(substr($value10, 0,2)))
							{
								echo "<option SELECTED value=\"".$value10."\">".lang_unslug($value10)."</option>";
							}
							else
							{
								echo "<option value=\"".$value10."\">".lang_unslug($value10)."</option>";
							}
						}
					?>
				</select>
				<p class="help-block">For more languages, please refer to <a target="_blank" href="http://www.w3schools.com/tags/ref_language_codes.asp">ISO Language Codes</a></p>
			</div>
			
			<div class="controls inline">
				<input class="normal-checkbox" id="multilanguage" type="checkbox" name="data[Setting][15][multilanguage]" value="yes" <?php echo (strpos($mySetting[15]['Setting']['value'] , chr(13).chr(10))===FALSE?'':'CHECKED'); ?>/>						
				<label class="control-label label-checkbox">Multi Language</label>
			</div>
		</div>
		
		<div id="optlang" <?php echo (strpos($mySetting[15]['Setting']['value'] , chr(13).chr(10))===FALSE?'style="display:none"':''); ?>>
			<div class="container-box small" style="margin-top: -10px;">
				<?php
					foreach ($langlist as $key10 => $value10) 
					{	
						echo '<div class="control-group group-checkbox">';
						echo '<input type="hidden" value="'.$value10.'">';
						echo '<div class="controls inline">';
						echo '<input class="normal-checkbox" type="checkbox" name="data[Setting][15][optlang]['.$value10.']" value="fixed" '.(stripos($mySetting[15]['Setting']['value'], $value10)===FALSE?'':'CHECKED '.(strtolower(substr($mySetting[15]['Setting']['value'], 0 , 2))==strtolower(substr($value10, 0,2))?'DISABLED':'')).'/>';
						echo '<label class="control-label label-checkbox">'.lang_unslug($value10).'</label>';
						echo '</div>';
						echo '</div>';
					}
				?>
			</div>
			
			<div id="lang_interaction" class="control-group">
				<div class="controls">
					<input style="display: none" maxlength="2" class="input-xmini" type="text" value="" placeholder="Code"/>
					<input style="display: none" class="input-medium" type="text" value="" placeholder="Language"/>
					<a class="btn btn-info add_lang" href="javascript:void(0)">Add More Language...</a>
					<a style="display: none" class="btn cancel_lang" href="javascript:void(0)">Cancel</a>
				</div>
			</div>
		</div>
<!-- 		END OF LANGUAGE SETTINGS -->
		<?php			
			// Google Analytics Code ...
			$value['counter'] = 8;
			$value['key'] = 'form-'.string_unslug($mySetting[$value['counter']]['Setting']['key']);
			$value['validation'] = '';
			$value['value'] = $mySetting[$value['counter']]['Setting']['value'];
			$value['model'] = 'Setting';			
			$value['input_type'] = 'text';
			echo $this->element('input_'.$value['input_type'] , $value);
			
			// Table View Format...
			$value['counter'] = 16;
			$value['key'] = 'form-'.string_unslug($mySetting[$value['counter']]['Setting']['key']);
			$value['validation'] = 'not_empty';
			$value['value'] = $mySetting[$value['counter']]['Setting']['value'];
			$value['model'] = 'Setting';			
			$value['input_type'] = 'dropdown';
			$value['list'][0]['id'] = 'simple';
			$value['list'][0]['name'] = 'Simple';
			$value['list'][1]['id'] = 'complex';
			$value['list'][1]['name'] = 'Complex';
			$value['p'] = "Table view mode in Admin Panel.";
			echo $this->element('input_'.$value['input_type'] , $value);
			unset($value['list']);
			unset($value['p']);
		?>
		<div class="alert alert-info full fl">
			<strong>Page Inserts</strong>
		</div>
		<?php
			// HEADER ...
			$value['counter'] = 5;
			$value['key'] = 'form-'.string_unslug($mySetting[$value['counter']]['Setting']['key']);
			$value['validation'] = '';
			$value['value'] = $mySetting[$value['counter']]['Setting']['value'];
			$value['model'] = 'Setting';			
			$value['input_type'] = 'textarea';
			$value['p'] = 'Usually you can add external CSS or JavaScript. HTML is OK.';
			echo $this->element('input_'.$value['input_type'] , $value);
			unset($value['p']);
			
			// TOP INSERT...
			$value['counter'] = 6;
			$value['key'] = 'form-'.string_unslug($mySetting[$value['counter']]['Setting']['key']);
			$value['validation'] = '';
			$value['value'] = $mySetting[$value['counter']]['Setting']['value'];
			$value['model'] = 'Setting';			
			$value['input_type'] = 'textarea';
			$value['p'] = 'Insert codes right after the body tag starts.';
			echo $this->element('input_'.$value['input_type'] , $value);
			unset($value['p']);
			
			// BOTTOM INSERT ...
			$value['counter'] = 7;
			$value['key'] = 'form-'.string_unslug($mySetting[$value['counter']]['Setting']['key']);
			$value['validation'] = '';
			$value['value'] = $mySetting[$value['counter']]['Setting']['value'];
			$value['model'] = 'Setting';			
			$value['input_type'] = 'textarea';
			$value['p'] = 'Insert codes right before the body tag end.';
			echo $this->element('input_'.$value['input_type'] , $value);
			unset($value['p']);
		?>
		
		<div class="alert alert-info full fl">
			<strong>Media Settings</strong>
		</div>		
		<div class="control-group">            
			<label style="color: red;" class="control-label">Display Image</label>
			<div class="controls dimension">
				<input REQUIRED name="data[Setting][9][value]" type="text" class="small" value="<?php echo $mySetting[9]['Setting']['value']; ?>" placeholder="Width" /> <span>x</span>
				<input type="hidden" value="form-display_image_width" name="data[Setting][9][key]"/>
				<input type="hidden" value="not_empty|is_numeric" size="100" name="data[Setting][9][validation]"/>
				<input REQUIRED name="data[Setting][10][value]" type="text" class="small" value="<?php echo $mySetting[10]['Setting']['value']; ?>" placeholder="Height" />
				<input type="hidden" value="form-display_image_height" name="data[Setting][10][key]"/>
				<input type="hidden" value="not_empty|is_numeric" size="100" name="data[Setting][10][validation]"/>
				<p class="help-block">Width x Height (px)</p>
			</div>
			<div class="controls inline" style="position: relative; left: -70px;">
				<input <?php echo (empty($mySetting[11]['Setting']['value'])?'':'CHECKED'); ?> type="checkbox" name="data[Setting][11][value]" value="1"/><label>Enable Cropping</label>
			</div>
		</div>
		
		<div class="control-group">            
			<label style="color: red;" class="control-label">Thumbnail Image</label>
			<div class="controls dimension">
				<input REQUIRED name="data[Setting][12][value]" type="text" class="small" value="<?php echo $mySetting[12]['Setting']['value']; ?>" placeholder="Width" /> <span>x</span>
				<input type="hidden" value="form-thumbnail_image_width" name="data[Setting][12][key]"/>
				<input type="hidden" value="not_empty|is_numeric" size="100" name="data[Setting][12][validation]"/>
				<input REQUIRED name="data[Setting][13][value]" type="text" class="small" value="<?php echo $mySetting[13]['Setting']['value']; ?>" placeholder="Height" />
				<input type="hidden" value="form-thumbnail_image_height" name="data[Setting][13][key]"/>
				<input type="hidden" value="not_empty|is_numeric" size="100" name="data[Setting][13][validation]"/>
				<p class="help-block">Width x Height (px)</p>
			</div>
			<div class="controls inline" style="position: relative; left: -70px;">
				<input <?php echo (empty($mySetting[14]['Setting']['value'])?'':'CHECKED'); ?> type="checkbox" name="data[Setting][14][value]" value="1"/><label>Enable Cropping</label>
			</div>
		</div>
	
	<!-- PAGE STATUS -->
		<div class="alert alert-info full fl">
			<strong>Additional Info</strong>
		</div>
		<div id="inputWrapper">
		<?php
			// ADDITIONAL INFO ...			
			foreach ($mySetting as $key10 => $value10) 
			{
				if(is_numeric($key10) && substr($value10['Setting']['key'], 0,7) == 'custom-')
				{
					// renew key ...
					$value10['Setting']['key'] = substr($value10['Setting']['key'], 7);

					// prepare element data ...
					$value = array();
					$value['validation'] = '';					
					$value['counter'] = $value10['Setting']['id'] - 1;
					$value['key'] = 'form-'.string_unslug($value10['Setting']['key']);
					$value['value'] = $value10['Setting']['value'];
					$value['model'] = 'Setting';
					$value['input_type'] = 'text';
					$initial = ($user['role_id'] <= 1?'special':'input');
					echo $this->element($initial.'_'.$value['input_type'] , $value);
				}
			}
		?>
		</div>
		<?php
			if($user['role_id'] <= 1)
			{
				?>
					<div class="control-group">
						<label class="control-label">(New Key)</label>
						<div class="controls">
							<input style="display: none" class="input-medium input_add_setting" type="text" size="200" value="" placeholder="Key"/>
							<a class="btn btn-info add_setting" href="javascript:void(0)">Add More Settings...</a>
							<a style="display: none" class="btn cancel_setting" href="javascript:void(0)">Cancel</a>
						</div>
					</div>
				<?php
			}
		?>
	<!-- SAVE BUTTON -->
		<div class="control-action">
			<button type="submit" class="btn btn-primary">Save Changes</button>
	        <button type="button" class="btn" onclick="javascript: window.location=site+'admin/settings'">Cancel</button>
	    </div>
	</fieldset>
<?php echo $this->Form->end(); ?>
</div>