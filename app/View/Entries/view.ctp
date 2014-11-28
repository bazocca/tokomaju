<div class="entries view">
<h2><?php echo __('Entry');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $entry['Entry']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Dbtype'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $this->Html->link($entry['Dbtype']['name'], array('controller' => 'dbtypes', 'action' => 'view', $entry['Dbtype']['id'])); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Title'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $entry['Entry']['title']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Slug'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $entry['Entry']['slug']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Description'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $entry['Entry']['description']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Media'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $this->Html->link($entry['Media']['title'], array('controller' => 'media', 'action' => 'view', $entry['Media']['id'])); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Parent Entry'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $this->Html->link($entry['ParentEntry']['title'], array('controller' => 'entries', 'action' => 'view', $entry['ParentEntry']['id'])); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Status'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $entry['Entry']['status']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Count'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $entry['Entry']['count']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Created'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $entry['Entry']['created']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Created By'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $entry['Entry']['created_by']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Modified'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $entry['Entry']['modified']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Modified By'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $entry['Entry']['modified_by']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Entry'), array('action' => 'edit', $entry['Entry']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Delete Entry'), array('action' => 'delete', $entry['Entry']['id']), null, sprintf(__('Are you sure you want to delete # %s?'), $entry['Entry']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Entries'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Entry'), array('action' => 'add')); ?> </li>
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
<div class="related">
	<h3><?php echo __('Related Entries');?></h3>
	<?php if (!empty($entry['ChildEntry'])):?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Dbtype Id'); ?></th>
		<th><?php echo __('Title'); ?></th>
		<th><?php echo __('Slug'); ?></th>
		<th><?php echo __('Description'); ?></th>
		<th><?php echo __('Media Id'); ?></th>
		<th><?php echo __('Parent Id'); ?></th>
		<th><?php echo __('Status'); ?></th>
		<th><?php echo __('Count'); ?></th>
		<th><?php echo __('Created'); ?></th>
		<th><?php echo __('Created By'); ?></th>
		<th><?php echo __('Modified'); ?></th>
		<th><?php echo __('Modified By'); ?></th>
		<th class="actions"><?php echo __('Actions');?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($entry['ChildEntry'] as $childEntry):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		?>
		<tr<?php echo $class;?>>
			<td><?php echo $childEntry['id'];?></td>
			<td><?php echo $childEntry['dbtype_id'];?></td>
			<td><?php echo $childEntry['title'];?></td>
			<td><?php echo $childEntry['slug'];?></td>
			<td><?php echo $childEntry['description'];?></td>
			<td><?php echo $childEntry['media_id'];?></td>
			<td><?php echo $childEntry['parent_id'];?></td>
			<td><?php echo $childEntry['status'];?></td>
			<td><?php echo $childEntry['count'];?></td>
			<td><?php echo $childEntry['created'];?></td>
			<td><?php echo $childEntry['created_by'];?></td>
			<td><?php echo $childEntry['modified'];?></td>
			<td><?php echo $childEntry['modified_by'];?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'entries', 'action' => 'view', $childEntry['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'entries', 'action' => 'edit', $childEntry['id'])); ?>
				<?php echo $this->Html->link(__('Delete'), array('controller' => 'entries', 'action' => 'delete', $childEntry['id']), null, sprintf(__('Are you sure you want to delete # %s?'), $childEntry['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Child Entry'), array('controller' => 'entries', 'action' => 'add'));?> </li>
		</ul>
	</div>
</div>
<div class="related">
	<h3><?php echo __('Related Entry Details');?></h3>
	<?php if (!empty($entry['EntryDetail'])):?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Entry Id'); ?></th>
		<th><?php echo __('Attribute Id'); ?></th>
		<th><?php echo __('Value'); ?></th>
		<th class="actions"><?php echo __('Actions');?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($entry['EntryDetail'] as $entryDetail):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		?>
		<tr<?php echo $class;?>>
			<td><?php echo $entryDetail['id'];?></td>
			<td><?php echo $entryDetail['entry_id'];?></td>
			<td><?php echo $entryDetail['attribute_id'];?></td>
			<td><?php echo $entryDetail['value'];?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'entry_details', 'action' => 'view', $entryDetail['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'entry_details', 'action' => 'edit', $entryDetail['id'])); ?>
				<?php echo $this->Html->link(__('Delete'), array('controller' => 'entry_details', 'action' => 'delete', $entryDetail['id']), null, sprintf(__('Are you sure you want to delete # %s?'), $entryDetail['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Entry Detail'), array('controller' => 'entry_details', 'action' => 'add'));?> </li>
		</ul>
	</div>
</div>
