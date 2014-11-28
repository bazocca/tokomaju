<div class="userDetails view">
<h2><?php echo __('User Detail');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $userDetail['UserDetail']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('User'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $this->Html->link($userDetail['User']['id'], array('controller' => 'users', 'action' => 'view', $userDetail['User']['id'])); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Attribute'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $this->Html->link($userDetail['Attribute']['id'], array('controller' => 'attributes', 'action' => 'view', $userDetail['Attribute']['id'])); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Value'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $userDetail['UserDetail']['value']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit User Detail'), array('action' => 'edit', $userDetail['UserDetail']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Delete User Detail'), array('action' => 'delete', $userDetail['UserDetail']['id']), null, sprintf(__('Are you sure you want to delete # %s?'), $userDetail['UserDetail']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List User Details'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New User Detail'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Users'), array('controller' => 'users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New User'), array('controller' => 'users', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Attributes'), array('controller' => 'attributes', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Attribute'), array('controller' => 'attributes', 'action' => 'add')); ?> </li>
	</ul>
</div>
