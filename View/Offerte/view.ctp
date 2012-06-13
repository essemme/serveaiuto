<div class="offerte view">
<h2><?php  echo __('Offerta');?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($offerta['Offerta']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Nome'); ?></dt>
		<dd>
			<?php echo h($offerta['Offerta']['nome']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('User'); ?></dt>
		<dd>
			<?php echo $this->Html->link($offerta['User']['username'], array('controller' => 'users', 'action' => 'view', $offerta['User']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Tipo'); ?></dt>
		<dd>
			<?php echo $this->Html->link($offerta['Tipo']['nome'], array('controller' => 'tipi', 'action' => 'view', $offerta['Tipo']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Offerta'); ?></dt>
		<dd>
			<?php echo h($offerta['Offerta']['offerta']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Telefono'); ?></dt>
		<dd>
			<?php echo h($offerta['Offerta']['telefono']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Email'); ?></dt>
		<dd>
			<?php echo h($offerta['Offerta']['email']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3>Azioni</h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Offerta'), array('action' => 'edit', $offerta['Offerta']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Offerta'), array('action' => 'delete', $offerta['Offerta']['id']), null, __('Are you sure you want to delete # %s?', $offerta['Offerta']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Offerte'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Offerta'), array('action' => 'add')); ?> </li>
	</ul>
</div>
