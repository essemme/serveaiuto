<div class="province index">
	<h2><?php echo __('Province');?></h2>
        <table class="table table-bordered table-striped">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('provincia');?></th>
			<th><?php echo $this->Paginator->sort('riferimenti');?></th>
			<th><?php echo $this->Paginator->sort('aperta');?></th>
			<th class="actions"><?php echo __('Actions');?></th>
	</tr>
	<?php
	foreach ($province as $provincia): ?>
	<tr>
		<td><?php echo h($provincia['Provincia']['id']); ?>&nbsp;</td>
		<td><?php echo h($provincia['Provincia']['provincia']); ?>&nbsp;</td>
		<td><?php echo h($provincia['Provincia']['riferimenti']); ?>&nbsp;</td>
		<td><?php echo h($provincia['Provincia']['aperta']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $provincia['Provincia']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $provincia['Provincia']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $provincia['Provincia']['id']), null, __('Are you sure you want to delete # %s?', $provincia['Provincia']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	
        <div class="actions">
            <p>
                <?php echo $this->Html->link('Aggiungi provincia', '/province/add'); ?>
            </p>            
        </div>

	<div class="pagination">
	<?php
		echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
</div>

