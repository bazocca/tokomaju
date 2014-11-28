<?php
	if(is_array($data)) extract($data , EXTR_SKIP);
	$this->Html->addCrumb('Master', '#');
	$this->Html->addCrumb('Database', '/admin/master/types');
	if(!empty($myParentType))
	{
		$this->Html->addCrumb($myParentType['Type']['name'], '/admin/master/types/'.$myParentType['Type']['slug']);
	}
	if(empty($myType))
	{
		$this->Html->addCrumb('Add New', '/admin/master/types/'.(empty($myParentType)?'':$myParentType['Type']['slug'].'/').'add');
	}
	else
	{
		$this->Html->addCrumb('Edit '.$myType['Type']['name'], '/admin/master/types/'.(empty($myParentType)?'':$myParentType['Type']['slug'].'/').'edit/'.$myType['Type']['slug']);
	}
?>
<script type="text/javascript">
	$("a#master").addClass("active");
</script>
<div class="inner-header">
	<div class="title">
		<?php
			if(empty($myType))
			{
				echo '<h2>ADD NEW '.(empty($myParentType)?'':$myParentType['Type']['name']).' DATABASES</h2>';
			}
			else
			{
				echo '<h2>'.$myType['Type']['name'].'</h2>';
				?>
				<p id="id-title-description" class="title-description">Last updated by <a href="#"><?php echo (empty($myType['AccountModifiedBy']['username'])?$myType['AccountModifiedBy']['email']:$myType['AccountModifiedBy']['username']).'</a> at '.date_converter($myType['Type']['modified'], $mySetting['date_format'] , $mySetting['time_format']); ?></p>
				<?php
			}
		?>
	</div>
</div>
<div class="inner-content">
<?php
	$saveButton = (empty($myType)?'Add New':'Save Changes');
	echo '<form class="notif-change form-horizontal fl" accept-charset="utf-8" method="post" enctype="multipart/form-data" action="'.$imagePath.'admin/master/types/'.(empty($myParentType)?'':$myParentType['Type']['slug'].'/').(empty($myType)?'add':'edit/'.$myType['Type']['slug']).'">';	
?>
	<fieldset>
		<p class="notes important" style="color: red;font-weight: bold;">* Red input MUST NOT be empty.</p>
		
		<!-- COUNTER STARTS FROM ZERO !! -->
		<?php
			$counter = 0;
		?>	
		<?php
		    $value = array();
			$value['counter'] = $counter++;
			$value['key'] = 'form-Name';
			$value['validation'] = 'not_empty';
			$value['value'] = $myType['Type']['name'];
			$value['model'] = 'Type';
			$value['input_type'] = 'text';
			$value['p'] = 'Keep it short and simple. Ex: Projects.';
			echo $this->element('input_'.$value['input_type'] , $value);
			
			$value = array();
			$value['counter'] = $counter++;
			$value['key'] = 'form-Description';
			$value['validation'] = '';
			$value['value'] = $myType['Type']['description'];
			$value['model'] = 'Type';
			$value['input_type'] = 'textarea';
			echo $this->element('input_'.$value['input_type'] , $value);
			
			// STAGGING place here ...
			$value = array();
			$value['counter'] = $counter++;
			$value['key'] = 'form-Stagging';
			$value['validation'] = '';
			$value['value'] = $myType['TypeMeta']['stagging'][0];
			$value['model'] = 'TypeMeta';			
			$value['input_type'] = 'checkbox';
			$value['p'] = 'CRUD options on Front-End.';
			$value['list'][0]['id'] = 'enable';
			$value['list'][0]['name'] = 'Enable';
			echo $this->element('input_'.$value['input_type'] , $value);
            
            // Turn ON / OFF for gallery addition func ...
            $value = array();
            $value['counter'] = $counter++;
            $value['key'] = 'form-Gallery';
            $value['validation'] = '';
            $value['value'] = $myType['TypeMeta']['gallery'][0];
            $value['model'] = 'TypeMeta';           
            $value['input_type'] = 'checkbox';
            $value['p'] = 'Turn On/Off for gallery additional function.';
            $value['list'][0]['id'] = 'enable';
            $value['list'][0]['name'] = 'Enable';
            echo $this->element('input_'.$value['input_type'] , $value);
			
			// Custom Title Key Name ...
			$value = array();
			$value['counter'] = $counter++;
			$value['key'] = 'form-Title_Key';
			$value['validation'] = 'is_alnum';
			$value['value'] = $myType['TypeMeta']['title_key'][0];
			$value['model'] = 'TypeMeta';
			$value['input_type'] = 'text';
			$value['p'] = 'User defined title key name.';
			$value['placeholder'] = "Title";
			echo $this->element('input_'.$value['input_type'] , $value);

			// Custom pagination per module ...
			$value = array();
			$value['counter'] = $counter++;
			$value['key'] = 'form-Pagination';
			$value['validation'] = 'is_numeric';
			$value['value'] = $myType['TypeMeta']['pagination'][0];
			$value['model'] = 'TypeMeta';
			$value['input_type'] = 'text';
			$value['inputsize'] = 'input-mini';
			$value['p'] = 'How many data showing on each page (front-end)';
			echo $this->element('input_'.$value['input_type'] , $value);
			// ------------------------- >>>
		?>
		<!-- UPDATE COUNTER STARTS FROM 101 !! -->
		<?php
			$counter = 100;
		?>
		<div class="alert alert-info full fl">
			<strong>Image Settings</strong>
		</div>
		<div class="control-group">            
			<label class="control-label">Display Size</label>
			<div class="controls dimension">
				<input name="data[TypeMeta][<?php echo $counter+1; ?>][value]" type="text" class="small" value="<?php echo $myType['TypeMeta']['display_width'][0]; ?>" placeholder="Width" /> <span>x</span>
				<input type="hidden" value="form-Display_Width" size="100" name="data[TypeMeta][<?php echo $counter+1; ?>][key]"/>
				<input type="hidden" value="is_numeric" size="100" name="data[TypeMeta][<?php echo $counter+1; ?>][validation]"/>
				<input name="data[TypeMeta][<?php echo $counter+2; ?>][value]" type="text" class="small" value="<?php echo $myType['TypeMeta']['display_height'][0]; ?>" placeholder="Height" />
				<input type="hidden" value="form-Display_Height" size="100" name="data[TypeMeta][<?php echo $counter+2; ?>][key]"/>
				<input type="hidden" value="is_numeric" size="100" name="data[TypeMeta][<?php echo $counter+2; ?>][validation]"/>
				<p class="help-block">Width x Height (px)</p>
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label">Display Crop</label>
			<div class="controls radio" style="position: relative; top:5px;">
				<input <?php echo (empty($myType['TypeMeta']['display_crop'][0])?'CHECKED':''); ?> type="radio" value="0" name="data[TypeMeta][<?php echo $counter+3; ?>][value]"/><label>None</label>				
				<input <?php echo ($myType['TypeMeta']['display_crop'][0]==1?'CHECKED':''); ?> type="radio" value="1" name="data[TypeMeta][<?php echo $counter+3; ?>][value]"/><label>Automatic</label>				
				<input <?php echo ($myType['TypeMeta']['display_crop'][0]==2?'CHECKED':''); ?> type="radio" value="2" name="data[TypeMeta][<?php echo $counter+3; ?>][value]"/><label>Manual</label>
				<input type="hidden" value="form-Display_Crop" name="data[TypeMeta][<?php echo $counter+3; ?>][key]"/>
			</div>
		</div>
		
		<div class="control-group">            
			<label class="control-label">Thumbnail Size</label>
			<div class="controls dimension">
				<input name="data[TypeMeta][<?php echo $counter+4; ?>][value]" type="text" class="small" value="<?php echo $myType['TypeMeta']['thumb_width'][0]; ?>" placeholder="Width" /> <span>x</span>
				<input type="hidden" value="form-Thumb_Width" size="100" name="data[TypeMeta][<?php echo $counter+4; ?>][key]"/>
				<input type="hidden" value="is_numeric" size="100" name="data[TypeMeta][<?php echo $counter+4; ?>][validation]"/>
				<input name="data[TypeMeta][<?php echo $counter+5; ?>][value]" type="text" class="small" value="<?php echo $myType['TypeMeta']['thumb_height'][0]; ?>" placeholder="Height" />
				<input type="hidden" value="form-Thumb_Height" size="100" name="data[TypeMeta][<?php echo $counter+5; ?>][key]"/>
				<input type="hidden" value="is_numeric" size="100" name="data[TypeMeta][<?php echo $counter+5; ?>][validation]"/>
				<p class="help-block">Width x Height (px)</p>
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label">Thumbnail Crop</label>
			<div class="controls radio" style="position: relative; top:5px;">
				<input <?php echo (empty($myType['TypeMeta']['thumb_crop'][0])?'CHECKED':''); ?> type="radio" value="0" name="data[TypeMeta][<?php echo $counter+6; ?>][value]"/><label>None</label>				
				<input <?php echo (!empty($myType['TypeMeta']['thumb_crop'][0])?'CHECKED':''); ?> type="radio" value="1" name="data[TypeMeta][<?php echo $counter+6; ?>][value]"/><label>Automatic</label>				
				<input type="hidden" value="form-Thumb_Crop" name="data[TypeMeta][<?php echo $counter+6; ?>][key]"/>
			</div>
		</div>
		
		<table class="list list-field sortable">
		<thead>
			<tr>
				<th>FIELD LABEL</th>
				<th>FIELD TYPE</th>
				<th class="action"></th>
			</tr>
		</thead>
		<tbody id="myInputWrapper">
			<?php
				if(!empty($myType))
				{
					foreach ($myType['TypeMeta'] as $key => $value) 
					{
						$value = $value['TypeMeta']; // SPECIAL CASE, COZ IT'S BEEN MODIFIED IN CONTROLLER !!
						if(substr($value['key'], 0 , 5) == 'form-')
						{
							?>
							<tr class="input_list">
								<td><h5><?php echo string_unslug(substr($value['key'], 5)); ?></h5></td>
								<td><?php echo $value['input_type']; ?></td>
								<td>
									<div class="btn-group" style="float:left;">
										<a class="btn btn-mini" href="#"><i class="icon-cog"></i></a>
										<a class="btn btn-mini dropdown-toggle" data-toggle="dropdown" href="#"><span class="caret"></span>&nbsp;</a>
										<ul class="dropdown-menu">
											<!--Change the url as needed-->
											<li><a alt="old" class="popup url edit-field" href="<?php echo $imagePath.'types/form_popup/edit'; ?>"><i class="icon-pencil"></i> Edit</a></li>
											<li><a class="delete-field" href="javascript:void(0)"><i class="icon-trash"></i> Delete</a></li>
										</ul>
									</div>
									<input type="hidden" name="data[TypeMeta][][key]" value="<?php echo $value['key']; ?>"/>
									<input type="hidden" name="data[TypeMeta][][value]" value="<?php echo $value['value']; ?>"/>
									<input type="hidden" name="data[TypeMeta][][input_type]" value="<?php echo $value['input_type']; ?>"/>
									<input type="hidden" name="data[TypeMeta][][validation]" value="<?php echo $value['validation']; ?>"/>
									<input type="hidden" name="data[TypeMeta][][instruction]" value="<?php echo $value['instruction']; ?>"/>
								</td>
							</tr>
							<?php
						}
					}
				}
			?>	
		</tbody>
		</table>
		<?php echo $this->Form->Html->link('Add New Field',array('controller'=>'types','action'=>'form_popup','add','admin'=>false),array('class'=>'btn btn-mini popup url'));	?>
	<!-- SAVE BUTTON -->
		<div class="control-action">
			<button type="submit" class="btn btn-primary"><?php echo $saveButton; ?></button>
        	<button type="button" class="btn" onclick="javascript: window.location=site+'admin/master/types<?php echo (empty($myParentType)?'':'/'.$myParentType['Type']['slug']); ?>'">Cancel</button>
		</div>
	</fieldset>
	</form>	
</div>