<div class="users index">
	<h2><?php echo __('Users');?></h2>
	<table class="table table-bordered table-striped" >
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('username');?></th>
			<th><?php echo $this->Paginator->sort('email');?></th>
			<th><?php echo $this->Paginator->sort('created');?><br /><?php echo $this->Paginator->sort('modified');?></th>
                        <th><?php echo $this->Paginator->sort('role_id');?></th>
                        <th><?php echo $this->Paginator->sort('provincia_id');?></th>
                        <th><?php echo $this->Paginator->sort('facebook_id');?></th>
			<th class="actions"><?php echo __('Actions');?></th>
	</tr>
	<?php
	foreach ($users as $user): ?>
	<tr>
		<td><?php echo h($user['User']['id']); ?>&nbsp;</td>
		<td><?php echo h($user['User']['username']); ?>&nbsp;</td>		
		<td><?php echo h($user['User']['email']); ?>&nbsp;</td>
		<td><?php echo h($user['User']['created']); ?><br />
                <?php echo h($user['User']['modified']); ?></td>
                <td><?php echo $roles[ $user['User']['role_id'] ] ?>&nbsp;</td>
                <td><?php echo $user['Provincia']['provincia']; ?>&nbsp;</td>                
		<td><?php echo h($user['User']['facebook_id']); ?>&nbsp;</td>
                
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $user['User']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $user['User']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $user['User']['id']), null, __('Are you sure you want to delete # %s?', $user['User']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
	));
	?>	</p>

	<div class="paginator">
	<?php
		echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
</div>

