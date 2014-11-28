<div class="login">
	<div class="header">
		<img src="<?php echo $imagePath; ?>images/logo.png" />
	</div>

	<div class="layout-header">
		<div class="sidebar-title">
			<h4>Forget Password</h4>
		</div>
	</div>
	
	<div class="layout-body">
		<div class="content">				
			<?php echo $this->Form->create('Account', array ('action'=>'forget'), array('class' => 'form-horizontal'));?>
				<fieldset>
					<?php
						echo $this->Session->flash('auth');
						echo $this->Session->flash('email');
						echo $this->Session->flash();
					?>
					<div class="control-group">
						<div class="controls">
							<?php echo $this->Form->input('email',array('label' => false, 'placeholder'=>'Email address', 'class' => 'input-xlarge')); ?>
						</div>
					</div>
					<div class="control-action">
						<button type="submit" class="btn btn-primary">Send Me My Password</button>
						<?php echo $this->Html->link('Back to Login',(empty($is_admin)?'':'/admin').'/login'); ?>
					</div>
				</fieldset>
			<?php echo $this->Form->end(); ?>
		</div>
	</div>
</div>