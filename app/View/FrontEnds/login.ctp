<div class="login">
	<div class="header">
		<img src="<?php echo $imagePath; ?>images/logo.png" />
	</div>

	<div class="layout-header">
		<div class="sidebar-title">
			<h4>Login</h4>
		</div>
	</div>
	
	<div class="layout-body">
		<div class="content">				
			<?php echo $this->Form->create(null, array ('url'=>'#'));?>
				<fieldset>
					<?php
						$status = $this->Session->flash('auth');
						if($status !== FALSE)
						{
							echo '<div class="alert alert-danger">';
							echo '<a class="close" data-dismiss="alert" href="#">X</a>';
							echo $status;
							echo '</div>';
						}
						$status = $this->Session->flash();
						if($status !== FALSE)
						{
							echo $status;
						}
					?>
					
					<?php echo $this->Form->input('email',array('label' => false, 'placeholder'=>'Email address', 'class' => 'input-xlarge')); ?>
					<?php echo $this->Form->input('password',array('label' => false, 'placeholder'=>'Password', 'class' => 'input-xlarge')); ?>
					<div class="control-action">
						<button type="submit" class="btn"><strong>Login</strong></button>
						<?php echo $this->Html->link('Forget password?',(empty($is_admin)?'':'/admin').'/forget'); ?>
					</div>
				</fieldset>
			<?php echo $this->Form->end(); ?>
		</div>
	</div><!--/row-->
</div>