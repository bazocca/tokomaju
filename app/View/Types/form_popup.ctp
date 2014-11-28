<script>
	$(document).ready(function(){
		if(this_element != "")
		{
			var myLabel = string_unslug( this_element.eq(0).val().substr(5)  );
			var value = this_element.eq(1).val();
			var input_type = this_element.eq(2).val();
			var validation = this_element.eq(3).val().toUpperCase();
			var instruction = this_element.eq(4).val();
			
			$('h4#title').html($('h4#title').html()+myLabel);
			$('input[type=text]#myLabel').val(myLabel);
			$('select#input_type').val(input_type);
			$('textarea#value').val(value);
			$('input[type=text]#instruction').val(instruction);
			$().val(get_input_attrib(validation,'not_empty'));
			$('input[type=checkbox]#not_empty').attr('checked' , ( validation.indexOf('NOT_EMPTY')<0?false:true));
			$('div.validation input[type=checkbox]').each(function(index){
				var bigId = $(this).attr('id').toUpperCase();
				$(this).attr('checked' , ( validation.indexOf(bigId)<0?false:true));
				if(bigId == 'MIN_LENGTH' || bigId == 'MAX_LENGTH')
				{
					$(this).parent().find('input[type=text].input_attribute').val(get_input_attrib(validation,bigId));
				}
			});
		}
		validation_check($('.field_type option:selected').val());
		$('.field_type').change(function(){
			validation_check($(this).val());
		});
	});
</script>

<div id="upload-popup" class="upload-popup form-popup">
	<?php
		$saveButton = ($state=='add'?'Add New':'Update Field');
		echo $this->Form->create('Type', array('id'=>'TypeFormPopupForm','action'=>'form_popup/'.$state,'type'=>'file','class'=>'notif-change form-horizontal fl','inputDefaults' => array('label' =>false , 'div' => false)));
	?>
		<div class="layout-header">
			<div class="sidebar-title">
				<h4 id="title"><?php echo ($state=='add'?'Add New Field':'Edit '); ?></h4>
				<a href="javascript:void(0);" class="close"><i class="icon-remove icon-white"></i></a>
			</div>
		</div>
		
		<div class="layout-content">
			<div class="popup-content">
				<div style="display: none" id="form-error" class="alert alert-danger"></div>
				<p class="notes important" style="color: red;font-weight: bold;">* Red input MUST NOT be empty.</p>
				
				<fieldset>
					<div class="control-group" <?php echo ($state=='add'?'':'style="display:none"'); ?>>            
						<label style="color: red;" class="control-label">Field Label<span></span></label>
						<div class="controls">
							<input REQUIRED id="myLabel" name="data[TypeMeta][key]" type="text" class="large" value="" />
							<p class="help-block">Apply a name for this field. Keep it short and simple.</p>
						</div>
					</div>

					<div class="control-group">            
						<label style="color: red;" class="control-label">Field Type</label>
						<div class="controls inline">
							<select id="input_type" name="data[TypeMeta][input_type]" class="field_type" <?php echo ($state=='add'?'':'DISABLED'); ?>>
								<?php
									foreach ($inputType as $key10 => $value10)
									{
										if($value10 == 'text') // SET AS DEFAULT...
										{
											echo "<option SELECTED value=\"".$value10."\">".$value10."</option>";
										}
										else
										{
											echo "<option value=\"".$value10."\">".$value10."</option>";
										}
									}
								?>
							</select>
						</div>
						
						<div class="controls inline">
							<input id="not_empty" type="checkbox" name="data[TypeMeta][validation][not_empty]" value="yes" />						
							<label class="control-label">Required?</label>
						</div>
					</div>
					
					<div class="control-group options">            
						<div class="controls">
							<textarea style="resize: vertical;" id="value" name="data[TypeMeta][value][option]" class="large" value="" rows="2"></textarea>
							<input id="typeMetaValueState" type="hidden" name="data[TypeMeta][value][state]" value="">
							<p class="help-block">Provide options, separated by line.</p>
						</div>
					</div>
					
					<div class="control-group">            
						<label class="control-label">Field Instructions</label>
						<div class="controls">
							<input id="instruction" name="data[TypeMeta][instruction]" type="text" class="large" value="" />
							<p class="help-block">Give user some hints.</p>
						</div>
					</div>
					
					<div class="alert alert-info full fl">
						<strong>Validations</strong>
					</div>
					
					<div class="validation row-fluid">
						<div class="span6">
							<div class="control-group is_numeric">            
								<div class="controls inline">
									<input id="is_numeric" type="checkbox" name="data[TypeMeta][validation][is_numeric]" value="no" />
									<label class="control-label">Numeric String</label>
								</div>
							</div>
							<div class="control-group is_email">            
								<div class="controls inline">
									<input id="is_email" type="checkbox" name="data[TypeMeta][validation][is_email]" value="no" />
									<label class="control-label">Email Field</label>
								</div>
							</div>
							<div class="control-group is_url">            
								<div class="controls inline">
									<input id="is_url" type="checkbox" name="data[TypeMeta][validation][is_url]" value="no" />
									<label class="control-label">URL Field</label>
								</div>
							</div>
							
							<div class="control-group browse_module">
								<div class="controls inline">
									<input id="browse_module" type="checkbox" name="data[TypeMeta][validation][browse_module]" value="no" />
									<label class="control-label">Taken from DB (no options needed)</label>
								</div>
							</div>
						</div>
						<div class="span6">
							<div class="control-group is_alnum">            
								<div class="controls inline">
									<input id="is_alnum" type="checkbox" name="data[TypeMeta][validation][is_alnum]" value="no" />
									<label class="control-label">Alphanumeric</label>
								</div>
							</div>
							<div class="control-group min_length">            
								<div class="controls inline">
									<input id="min_length" class="input_attribute" type="checkbox" name="data[TypeMeta][validation][min_length][state]" value="no" />
									<label class="control-label">Min length</label>
									<input name="data[TypeMeta][validation][min_length][value]" type="text" value="" class="small input_attribute" />
								</div>
							</div>						
							<div class="control-group max_length">
								<div class="controls inline">
									<input id="max_length" class="input_attribute" type="checkbox" name="data[TypeMeta][validation][max_length][state]" value="no" />
									<label class="control-label">Max length</label>
									<input name="data[TypeMeta][validation][max_length][value]" type="text" value="" class="small input_attribute" />
								</div>
							</div>
						</div>
					</div>
				</fieldset>
			</div>
			<div class="popup-action">
				<input type="submit" class="btn btn-primary" value="<?php echo $saveButton; ?>" />
				<input type="reset" class="btn" value="Cancel" onclick="javascript: $.colorbox.close();"/>
			</div>
		</div>
	<?php echo $this->Form->end(); ?>
</div>