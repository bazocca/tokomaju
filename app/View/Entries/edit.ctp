<div class="entries form">
<?php echo $this->Form->create('Entry');?>
	<fieldset>
		<legend><?php echo __('Edit Entry'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('dbtype_id');
		echo $this->Form->input('title');
		echo $this->Form->input('slug');
		echo $this->Form->input('description');
		echo $this->Form->input('media_id');
		echo $this->Form->input('parent_id');
		echo $this->Form->input('status');
		echo $this->Form->input('count');
		echo $this->Form->input('created_by');
		echo $this->Form->input('modified_by');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit'));?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('Delete'), array('action' => 'delete', $this->Form->value('Entry.id')), null, sprintf(__('Are you sure you want to delete # %s?'), $this->Form->value('Entry.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Entries'), array('action' => 'index'));?></li>
		<li><?php echo $this->Html->link(__('List Dbtypes'), array('controller' => 'dbtypes', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Dbtype'), array('controller' => 'dbtypes', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Media'), array('controller' => 'media', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Media'), array('controller' => 'media', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Entries'), array('controller' => 'entries', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Parent Entry'), array('controller' => 'entries', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Entry Details'), array('controller' => 'entry_details', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Entry Detail'), array('controller' => 'entry_details', 'action' => 'add')); ?> </li>
	</ul>
</div>