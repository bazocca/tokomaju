<?php
	$this->Html->addCrumb('Users', '/admin/users');
	$this->Html->addCrumb('Add New', '/admin/users/add');
	
	// today date !!
	$nowDate = getdate();
	$year = $nowDate['year'];	
?>
<script type="text/javascript">
	$("a#users").addClass("active");
</script>

<div class="inner-header">
	<div class="title">
		<h2>ADD USER</h2>				
	</div>
</div>

<div class="inner-content">		

<?php
	echo $this->Form->create('User', array('action'=>'add','type'=>'file','class'=>'notif-change form-horizontal fl','inputDefaults' => array('label' =>false , 'div' => false)));	
?>
	<fieldset>
		<p class="notes important" style="color: red;font-weight: bold;">* Red input MUST NOT be empty.</p>
	
		<div class="control-group">            
			<label style="color: red;" class="control-label">Firstname</label>
			<div class="controls">
				<input REQUIRED class="input-xlarge" type="text" value="<?php echo $_POST['data']['User']['firstname']; ?>" name="data[User][firstname]"/>								
			</div>
		</div>
		
		<div class="control-group">            
			<label class="control-label">Lastname</label>
			<div class="controls">				
				<input class="input-xlarge" type="text" value="<?php echo $_POST['data']['User']['lastname']; ?>" name="data[User][lastname]"/>
			</div>
		</div>
		
		<div class="control-group">            
			<label style="color: red;" class="control-label">Gender</label>
			<div class="controls">				
				<label class="radio inline">
				  <input REQUIRED type="radio" name="data[UserMeta][gender]" value="male" <?php echo ($_POST['data']['UserMeta']['gender']=='male'?'CHECKED':''); ?>>
				  Male
				</label>
				<label class="radio inline">
				  <input REQUIRED type="radio" name="data[UserMeta][gender]" value="female" <?php echo ($_POST['data']['UserMeta']['gender']=='female'?'CHECKED':''); ?>>
				  Female
				</label>
			</div>
		</div>
		
		<div class="control-group">            
			<label style="color: red;" class="control-label">Address</label>
			<div class="controls">				
				<input REQUIRED class="input-xlarge" type="text" value="<?php echo $_POST['data']['UserMeta']['address']; ?>" name="data[UserMeta][address]"/>
			</div>
		</div>
		
		<div class="control-group">            
			<label class="control-label">ZIP/Postal Code</label>
			<div class="controls">				
				<input class="input-small" type="text" value="<?php echo $_POST['data']['UserMeta']['zip_code']; ?>" name="data[UserMeta][zip_code]"/>
			</div>
		</div>
		
		<div class="control-group">            
			<label style="color: red;" class="control-label">City</label>
			<div class="controls">				
				<input REQUIRED class="input-xlarge" type="text" value="<?php echo $_POST['data']['UserMeta']['city']; ?>" name="data[UserMeta][city]"/>
			</div>
		</div>
		
		<div class="control-group">            
			<label class="control-label">Phone</label>
			<div class="controls">				
				<input class="input-medium" type="text" value="<?php echo $_POST['data']['UserMeta']['phone']; ?>" name="data[UserMeta][phone]"/>
			</div>
		</div>
		
		<div class="control-group">            
			<label style="color: red;" class="control-label">Mobile Phone</label>
			<div class="controls">				
				<input REQUIRED class="input-medium" type="text" value="<?php echo $_POST['data']['UserMeta']['mobile_phone']; ?>" name="data[UserMeta][mobile_phone]"/>
			</div>
		</div>
		
		<div class="control-group">
			<label style="color: red;" class="control-label">Date of Birth</label>
			<div class="controls">
				<select class="input-mini" name="data[UserMeta][dob_day]">
					<?php
						for($i=1 ; $i <= 31 ; ++$i)
						{
							echo "<option ".($_POST['data']['UserMeta']['dob_day']==$i?'SELECTED':'')." value='".$i."'>".sprintf("%02d" , $i)."</option>";
						}
					?>
				</select>
				
				<select class="input-medium" name="data[UserMeta][dob_month]">
					<?php
						for($i = 1 ; $i <= 12 ; ++$i)
						{
							echo "<option ".($_POST['data']['UserMeta']['dob_month']==$i?'SELECTED':'')." value='".$i."'>".strtoupper(date("F", mktime(0, 0, 0, $i, 10)))."</option>";
						}
					?>
				</select>
				
				<select class="input-small" name="data[UserMeta][dob_year]">
				  <?php
				  	for($i=100 ; $i >= 0 ; --$i)
					{
						$tempval = $year - $i;
						echo '<option '.($_POST['data']['UserMeta']['dob_year']==$tempval?'SELECTED':'').' value="'.$tempval.'">'.$tempval.'</option>';
					}
				  ?>
				</select>
			</div>
		</div>
		
		<div class="control-group">            
			<label class="control-label">Job</label>
			<div class="controls">				
				<input class="input-large" type="text" value="<?php echo $_POST['data']['UserMeta']['job']; ?>" name="data[UserMeta][job]"/>
			</div>
		</div>
		
		<div class="control-group">            
			<label class="control-label">Company</label>
			<div class="controls">				
				<input class="input-large" type="text" value="<?php echo $_POST['data']['UserMeta']['company']; ?>" name="data[UserMeta][company]"/>
			</div>
		</div>
		
		<div class="control-group">            
			<label class="control-label">Company Address</label>
			<div class="controls">				
				<input class="input-xlarge" type="text" value="<?php echo $_POST['data']['UserMeta']['company_address']; ?>" name="data[UserMeta][company_address]"/>
			</div>
		</div>
		
	<!-- SAVE BUTTON -->
		<div class="control-action">
			<button type="submit" class="btn btn-primary">Add New</button>
        	<button type="button" class="btn" onclick="javascript: window.location=site+'admin/users'">Cancel</button>
		</div>
	</fieldset>
<?php echo $this->Form->end(); ?>
	
</div>