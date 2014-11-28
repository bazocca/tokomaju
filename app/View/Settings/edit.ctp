<div class="settings form">
<?php echo $this->Form->create('Setting');?>
	<fieldset>
		<legend><?php echo __('Edit Setting'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('option');
		echo $this->Form->input('value');
		echo $this->Form->input('Media');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit'));?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('Delete'), array('action' => 'delete', $this->Form->value('Setting.id')), null, sprintf(__('Are you sure you want to delete # %s?'), $this->Form->value('Setting.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Settings'), array('action' => 'index'));?></li>
		<li><?php echo $this->Html->link(__('List Media'), array('controller' => 'media', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Media'), array('controller' => 'media', 'action' => 'add')); ?> </li>
	</ul>
</div>