<?php
	$this->Html->addCrumb('Accounts', '/admin/accounts');
	$this->Html->addCrumb($myData['User']['firstname'].' '.$myData['User']['lastname'], '/admin/accounts/edit/'.$id);	
?>
<script type="text/javascript">
	$("a#accounts").addClass("active");
</script>

<div class="inner-header">
	<div class="title">
		<h2><?php echo $myData['User']['firstname']; ?>'S ACCOUNT</h2>
		<p id="id-title-description" class="title-description">Last updated by <a href="#"><?php echo (empty($myData['ParentModifiedBy']['username'])?$myData['ParentModifiedBy']['email']:$myData['ParentModifiedBy']['username']).'</a> on '.date_converter($myData['Account']['modified'], $mySetting['date_format'] , $mySetting['time_format']); ?></p>
	</div>
</div>

<div class="inner-content">		
	
<?php
	echo $this->Form->create('Account', array('action'=>'edit/'.$id,'type'=>'file','class'=>'notif-change form-horizontal fl','inputDefaults' => array('label' =>false , 'div' => false)));	
?>
	<fieldset>
		<p class="notes important" style="color: red;font-weight: bold;">* Red input MUST NOT be empty.</p>
		<div class="control-group">            
			<label style="color: red;" class="control-label">Username</label>
			<div class="controls">				
				<input REQUIRED class="input-xlarge" value="<?php echo (isset($_POST['data']['Account']['username'])?$_POST['data']['Account']['username']:$myData['Account']['username']); ?>" type="text" size="40" name="data[Account][username]"/>
			</div>
		</div>
		
		<div class="control-group">            
			<label style="color: red;" class="control-label">E-mail</label>
			<div class="controls">				
				<input REQUIRED class="input-xlarge" value="<?php echo (isset($_POST['data']['Account']['email'])?$_POST['data']['Account']['email']:$myData['Account']['email']); ?>" type="text" size="40" name="data[Account][email]"/>								
				<p class="help-block">Please enter a valid email address.</p>								
			</div>
		</div>
		
		<div class="control-group">            
			<label class="control-label">New Password</label>
			<div class="controls">
				<input type="password" style="display: none;">				
				<input pattern=".{5,}" class="input-xlarge" type="password" name="data[Account][password]"/>
				<p class="help-block">Password must be at least 5 characters long.</p>				
			</div>
		</div>
		
		<div class="control-group">            
			<label class="control-label">Confirm Password</label>
			<div class="controls">				
				<input pattern=".{5,}" class="input-xlarge" type="password" name="data[Account][confirm]"/>								
			</div>
		</div>	
		
	<!-- SAVE BUTTON -->
		<div class="control-action">
			<button type="submit" class="btn btn-primary">Save Changes</button>
        	<button type="button" class="btn" onclick="javascript: window.location=site+'admin/accounts'">Cancel</button>
		</div>
	</fieldset>
<?php echo $this->Form->end(); ?>	
	
</div>