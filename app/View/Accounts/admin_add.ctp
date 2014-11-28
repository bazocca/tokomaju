<?php
	$this->Html->addCrumb('Accounts', '/admin/accounts');
	$this->Html->addCrumb('Add New', '/admin/accounts/add');	
?>
<script type="text/javascript">
	$("a#accounts").addClass("active");
	$(document).ready(function(){
		$.fn.changeRoleAction = function(e){
			$(e).nextAll("p.help-block").html( $("input[type=hidden]#role-"+$(e).val()).val() );
		}
		$("select#select-role").change(function(){
			$.fn.changeRoleAction(this);
		});		
		$.fn.changeRoleAction("select#select-role");
	});
</script>

<div class="inner-header">
	<div class="title">
		<h2>ADD ACCOUNT</h2>				
	</div>
</div>

<div class="inner-content">		

<?php
	echo $this->Form->create('Account', array('action'=>'add','type'=>'file','class'=>'notif-change form-horizontal fl','inputDefaults' => array('label' =>false , 'div' => false)));	
?>
	<fieldset>
		<p class="notes important" style="color: red;font-weight: bold;">* Red input MUST NOT be empty.</p>
		
		<div class="control-group">            
			<label style="color: red;" class="control-label">User ID</label>
			<div class="controls">
				<input REQUIRED id="user" class="input-medium" value="<?php echo $_POST['data']['temp']['username']; ?>" type="text" readonly="true" name="data[temp][username]"/>
				<input type="hidden" name="data[Account][user_id]" value="<?php echo $_POST['data']['Account']['user_id']; ?>" />
				<?php echo $this->Form->Html->link('Browse',array('controller'=>'users','action'=>'index?popup=init','admin'=>true),array('class'=>'btn btn-info get-from-table'));	?>
				<p class="help-block">Haven't been registered yet? Please <?php echo $this->Form->Html->link('register<img style="max-width:100%;" alt="External Icon" src="'.$imagePath.'img/external-icon.gif">',array('controller'=>'users','action'=>'add'),array("target"=>"SingleSecondaryWindowName","onclick"=>"javascript:openRequestedSinglePopup(this.href); return false;" , "escape" => false)); ?> first as user.</p>	
			</div>
		</div>
		
		<div class="control-group">            
			<label style="color: red;" class="control-label">Username</label>
			<div class="controls">				
				<input REQUIRED value="<?php echo $_POST['data']['Account']['username']; ?>" class="input-xlarge" type="text" name="data[Account][username]"/>
			</div>
		</div>
		
		<div class="control-group">            
			<label style="color: red;" class="control-label">Role</label>
			<div class="controls">
				<select id="select-role" name="data[Account][role_id]">
					<?php
						foreach ($listRoles as $key => $value)
						{
							echo "<option ".($_POST['data']['Account']['role_id']==$value['Role']['id']?'SELECTED':'')." value=\"".$value['Role']['id']."\">".$value['Role']['name']."</option>";
						}
					?>
				</select>
				<?php
					foreach ($listRoles as $key => $value)
					{
						echo "<input type='hidden' id='role-".$value['Role']['id']."' value='".$value['Role']['description']."' />";
					}
				?>
				<p class="help-block"></p>
			</div>
		</div>
		
		<div class="control-group">            
			<label style="color: red;" class="control-label">E-mail</label>
			<div class="controls">				
				<input REQUIRED class="input-xlarge" type="text" value="<?php echo $_POST['data']['Account']['email']; ?>" name="data[Account][email]"/>
				<p class="help-block">Please enter a valid email address.</p>								
			</div>
		</div>
		
		<div class="control-group">            
			<label style="color: red;" class="control-label">Password</label>
			<div class="controls">
				<input type="password" style="display: none;">				
				<input REQUIRED pattern=".{5,}" class="input-xlarge" type="password" size="40" name="data[Account][password]"/>
				<p class="help-block">Password must be at least 5 characters long.</p>				
			</div>
		</div>
		
		<div class="control-group">            
			<label style="color: red;" class="control-label">Confirm Password</label>
			<div class="controls">				
				<input REQUIRED pattern=".{5,}" class="input-xlarge" type="password" size="40" name="data[Account][confirm]"/>								
			</div>
		</div>
		
	<!-- SAVE BUTTON -->
		<div class="control-action">
			<button type="submit" class="btn btn-primary">Add New</button>
        	<button type="button" class="btn" onclick="javascript: window.location=site+'admin/accounts'">Cancel</button>
		</div>
	</fieldset>
<?php echo $this->Form->end(); ?>
	
</div>