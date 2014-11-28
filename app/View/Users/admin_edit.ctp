<?php
	$this->Html->addCrumb('Users', '/admin/users');
	$this->Html->addCrumb($myData['User']['firstname'].' '.$myData['User']['lastname'], '/admin/users/edit/'.$id);
	
	// today date !!
	$nowDate = getdate();
	$year = $nowDate['year'];	
?>
<script type="text/javascript">
	$("a#users").addClass("active");
</script>

<div class="inner-header">
	<div class="title">
		<h2><?php echo $myData['User']['firstname'].' '.$myData['User']['lastname']; ?></h2>
		<p id="id-title-description" class="title-description">Last updated by <a href="#"><?php echo (empty($myData['AccountModifiedBy']['username'])?$myData['AccountModifiedBy']['email']:$myData['AccountModifiedBy']['username']).'</a> on '.date_converter($myData['User']['modified'], $mySetting['date_format'] , $mySetting['time_format']); ?></p>					
	</div>
</div>

<div class="inner-content">		
	
<?php
	echo $this->Form->create('User', array('action'=>'edit/'.$id,'type'=>'file','class'=>'notif-change form-horizontal fl','inputDefaults' => array('label' =>false , 'div' => false)));	
?>
	<fieldset>
		<p class="notes important" style="color: red;font-weight: bold;">* Red input MUST NOT be empty.</p>
		<div class="control-group">            
			<label style="color: red;" class="control-label">Firstname</label>
			<div class="controls">
				<input REQUIRED class="input-xlarge" value="<?php echo (isset($_POST['data']['User']['firstname'])?$_POST['data']['User']['firstname']:$myData['User']['firstname']); ?>" type="text" size="40" name="data[User][firstname]"/>								
			</div>
		</div>
		
		<div class="control-group">            
			<label class="control-label">Lastname</label>
			<div class="controls">				
				<input class="input-xlarge" value="<?php echo (isset($_POST['data']['User']['lastname'])?$_POST['data']['User']['lastname']:$myData['User']['lastname']); ?>" type="text" size="40" name="data[User][lastname]"/>
			</div>
		</div>
		
		<div class="control-group">            
			<label style="color: red;" class="control-label">Gender</label>
			<div class="controls">				
				<label class="radio inline">
				  <input REQUIRED type="radio" name="data[UserMeta][gender]" value="male" <?php echo ((isset($_POST['data']['UserMeta']['gender'])?$_POST['data']['UserMeta']['gender']:$myData['UserMeta']['gender'])=='male'?'CHECKED':''); ?>>
				  Male
				</label>
				<label class="radio inline">
				  <input REQUIRED type="radio" name="data[UserMeta][gender]" value="female" <?php echo ((isset($_POST['data']['UserMeta']['gender'])?$_POST['data']['UserMeta']['gender']:$myData['UserMeta']['gender'])=='female'?'CHECKED':''); ?>>
				  Female
				</label>
			</div>
		</div>
		
		<div class="control-group">            
			<label style="color: red;" class="control-label">Address</label>
			<div class="controls">				
				<input REQUIRED class="input-xlarge" value="<?php echo (isset($_POST['data']['UserMeta']['address'])?$_POST['data']['UserMeta']['address']:$myData['UserMeta']['address']); ?>" type="text" size="40" name="data[UserMeta][address]"/>
			</div>
		</div>
		
		<div class="control-group">            
			<label class="control-label">ZIP/Postal Code</label>
			<div class="controls">
				<input class="input-small" value="<?php echo (isset($_POST['data']['UserMeta']['zip_code'])?$_POST['data']['UserMeta']['zip_code']:$myData['UserMeta']['zip_code']); ?>" type="text" name="data[UserMeta][zip_code]"/>
			</div>
		</div>
		
		<div class="control-group">            
			<label style="color: red;" class="control-label">City</label>
			<div class="controls">				
				<input REQUIRED class="input-xlarge" value="<?php echo (isset($_POST['data']['UserMeta']['city'])?$_POST['data']['UserMeta']['city']:$myData['UserMeta']['city']); ?>" type="text" size="40" name="data[UserMeta][city]"/>
			</div>
		</div>
		
		<div class="control-group">            
			<label class="control-label">Phone</label>
			<div class="controls">				
				<input class="input-medium" value="<?php echo (isset($_POST['data']['UserMeta']['phone'])?$_POST['data']['UserMeta']['phone']:$myData['UserMeta']['phone']); ?>" type="text" size="40" name="data[UserMeta][phone]"/>
			</div>
		</div>
		
		<div class="control-group">            
			<label style="color: red;" class="control-label">Mobile Phone</label>
			<div class="controls">				
				<input REQUIRED class="input-medium" value="<?php echo (isset($_POST['data']['UserMeta']['mobile_phone'])?$_POST['data']['UserMeta']['mobile_phone']:$myData['UserMeta']['mobile_phone']); ?>" type="text" size="40" name="data[UserMeta][mobile_phone]"/>
			</div>
		</div>
		
		<div class="control-group">
			<label style="color: red;" class="control-label">Date of Birth</label>
			<div class="controls">
				<select class="input-mini" name="data[UserMeta][dob_day]">
					<?php
						$dob_day = (isset($_POST['data']['UserMeta']['dob_day'])?$_POST['data']['UserMeta']['dob_day']:$myData['UserMeta']['dob_day']);
						for($i=1 ; $i <= 31 ; ++$i)
						{
							echo "<option ".( $dob_day ==$i?'SELECTED':'')." value='".$i."'>".sprintf("%02d" , $i)."</option>";
						}
					?>
				</select>
				
				<select class="input-medium" name="data[UserMeta][dob_month]">
					<?php
						$dob_month = (isset($_POST['data']['UserMeta']['dob_month'])?$_POST['data']['UserMeta']['dob_month']:$myData['UserMeta']['dob_month']);
						for($i = 1 ; $i <= 12 ; ++$i)
						{
							echo "<option ".($dob_month==$i?'SELECTED':'')." value='".$i."'>".strtoupper(date("F", mktime(0, 0, 0, $i, 10)))."</option>";
						}
					?>
				</select>
				
				<select class="input-small" name="data[UserMeta][dob_year]">
				  <?php
				  	$dob_year = (isset($_POST['data']['UserMeta']['dob_year'])?$_POST['data']['UserMeta']['dob_year']:$myData['UserMeta']['dob_year']);
				  	for($i=100 ; $i >= 0 ; --$i)
					{
						$tempval = $year - $i;
						echo '<option '.($dob_year==$tempval?'SELECTED':'').' value="'.$tempval.'">'.$tempval.'</option>';
					}
				  ?>
				</select>
			</div>
		</div>
		
		<div class="control-group">            
			<label class="control-label">Job</label>
			<div class="controls">				
				<input class="input-large" type="text" value="<?php echo (isset($_POST['data']['UserMeta']['job'])?$_POST['data']['UserMeta']['job']:$myData['UserMeta']['job']); ?>" name="data[UserMeta][job]"/>
			</div>
		</div>
		
		<div class="control-group">            
			<label class="control-label">Company</label>
			<div class="controls">				
				<input class="input-large" type="text" value="<?php echo (isset($_POST['data']['UserMeta']['company'])?$_POST['data']['UserMeta']['company']:$myData['UserMeta']['company']); ?>" name="data[UserMeta][company]"/>
			</div>
		</div>
		
		<div class="control-group">            
			<label class="control-label">Company Address</label>
			<div class="controls">				
				<input class="input-xlarge" type="text" value="<?php echo (isset($_POST['data']['UserMeta']['company_address'])?$_POST['data']['UserMeta']['company_address']:$myData['UserMeta']['company_address']); ?>" name="data[UserMeta][company_address]"/>
			</div>
		</div>
		
	<!-- SAVE BUTTON -->
		<div class="control-action">
			<button type="submit" class="btn btn-primary">Save Changes</button>
        	<button type="button" class="btn" onclick="javascript: window.location=site+'admin/users'">Cancel</button>
		</div>
	</fieldset>
<?php echo $this->Form->end(); ?>	
	
</div>