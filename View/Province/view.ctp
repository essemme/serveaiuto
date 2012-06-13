<div class="province view">
<h2><?php  echo __('Provincia');?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($provincia['Provincia']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Provincia'); ?></dt>
		<dd>
			<?php echo h($provincia['Provincia']['provincia']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Riferimenti'); ?></dt>
		<dd>
			<?php echo h($provincia['Provincia']['riferimenti']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Aperta'); ?></dt>
		<dd>
			<?php echo h($provincia['Provincia']['aperta']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Provincia'), array('action' => 'edit', $provincia['Provincia']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Provincia'), array('action' => 'delete', $provincia['Provincia']['id']), null, __('Are you sure you want to delete # %s?', $provincia['Provincia']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Province'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Provincia'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Users'), array('controller' => 'users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New User'), array('controller' => 'users', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Offerte'), array('controller' => 'offerte', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Offerta'), array('controller' => 'offerte', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Richieste'), array('controller' => 'richieste', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Richiesta'), array('controller' => 'richieste', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php echo __('Related Users');?></h3>
	<?php if (!empty($provincia['User'])):?>
        <table class="table table-bordered table-striped">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Username'); ?></th>
		<th><?php echo __('Password'); ?></th>
		<th><?php echo __('Email'); ?></th>
		<th><?php echo __('Provincia Id'); ?></th>
		<th><?php echo __('Password Reset'); ?></th>
		<th><?php echo __('Activation Code'); ?></th>
		<th><?php echo __('Active'); ?></th>
		<th><?php echo __('Role Id'); ?></th>
		<th><?php echo __('Created'); ?></th>
		<th><?php echo __('Modified'); ?></th>
		<th><?php echo __('Facebook Id'); ?></th>
		<th class="actions"><?php echo __('Actions');?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($provincia['User'] as $user): ?>
		<tr>
			<td><?php echo $user['id'];?></td>
			<td><?php echo $user['username'];?></td>
			<td><?php echo $user['password'];?></td>
			<td><?php echo $user['email'];?></td>
			<td><?php echo $user['provincia_id'];?></td>
			<td><?php echo $user['password_reset'];?></td>
			<td><?php echo $user['activation_code'];?></td>
			<td><?php echo $user['active'];?></td>
			<td><?php echo $user['role_id'];?></td>
			<td><?php echo $user['created'];?></td>
			<td><?php echo $user['modified'];?></td>
			<td><?php echo $user['facebook_id'];?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'users', 'action' => 'view', $user['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'users', 'action' => 'edit', $user['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'users', 'action' => 'delete', $user['id']), null, __('Are you sure you want to delete # %s?', $user['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New User'), array('controller' => 'users', 'action' => 'add'));?> </li>
		</ul>
	</div>
</div>
<div class="related">
	<h3><?php echo __('Related Offerte');?></h3>
	<?php if (!empty($provincia['Offerta'])):?>
        <table class="table table-bordered table-striped">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Nome'); ?></th>
		<th><?php echo __('User Id'); ?></th>
		<th><?php echo __('Tipo Id'); ?></th>
		<th><?php echo __('Offerta'); ?></th>
		<th><?php echo __('Telefono'); ?></th>
		<th><?php echo __('Email'); ?></th>
		<th><?php echo __('Terminata'); ?></th>
		<th><?php echo __('Verificata'); ?></th>
		<th class="actions"><?php echo __('Actions');?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($provincia['Offerta'] as $offerta): ?>
		<tr>
			<td><?php echo $offerta['id'];?></td>
			<td><?php echo $offerta['nome'];?></td>
			<td><?php echo $offerta['user_id'];?></td>
			<td><?php echo $offerta['tipo_id'];?></td>
			<td><?php echo $offerta['offerta'];?></td>
			<td><?php echo $offerta['telefono'];?></td>
			<td><?php echo $offerta['email'];?></td>
			<td><?php echo $offerta['terminata'];?></td>
			<td><?php echo $offerta['verificata'];?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'offerte', 'action' => 'view', $offerta['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'offerte', 'action' => 'edit', $offerta['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'offerte', 'action' => 'delete', $offerta['id']), null, __('Are you sure you want to delete # %s?', $offerta['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Offerta'), array('controller' => 'offerte', 'action' => 'add'));?> </li>
		</ul>
	</div>
</div>
<div class="related">
	<h3><?php echo __('Related Richieste');?></h3>
	<?php if (!empty($provincia['Richiesta'])):?>
        <table class="table table-bordered table-striped">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Cosa Serve'); ?></th>
		<th><?php echo __('Tipo Id'); ?></th>
		<th><?php echo __('Dove A Chi'); ?></th>
		<th><?php echo __('Testo'); ?></th>
		<th><?php echo __('Telefono'); ?></th>
		<th><?php echo __('Email'); ?></th>
		<th><?php echo __('User Id'); ?></th>
		<th><?php echo __('Scadenza'); ?></th>
		<th><?php echo __('Attiva'); ?></th>
		<th><?php echo __('In Evidenza'); ?></th>
		<th><?php echo __('Verificata'); ?></th>
		<th><?php echo __('Indirizzo Reale'); ?></th>
		<th><?php echo __('Localita Gmaps'); ?></th>
		<th><?php echo __('Completa'); ?></th>
		<th><?php echo __('Lat'); ?></th>
		<th><?php echo __('Lon'); ?></th>
		<th><?php echo __('Created'); ?></th>
		<th><?php echo __('Modified'); ?></th>
		<th class="actions"><?php echo __('Actions');?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($provincia['Richiesta'] as $richiesta): ?>
		<tr>
			<td><?php echo $richiesta['id'];?></td>
			<td><?php echo $richiesta['cosa_serve'];?></td>
			<td><?php echo $richiesta['tipo_id'];?></td>
			<td><?php echo $richiesta['dove_a_chi'];?></td>
			<td><?php echo $richiesta['testo'];?></td>
			<td><?php echo $richiesta['telefono'];?></td>
			<td><?php echo $richiesta['email'];?></td>
			<td><?php echo $richiesta['user_id'];?></td>
			<td><?php echo $richiesta['scadenza'];?></td>
			<td><?php echo $richiesta['attiva'];?></td>
			<td><?php echo $richiesta['in_evidenza'];?></td>
			<td><?php echo $richiesta['verificata'];?></td>
			<td><?php echo $richiesta['indirizzo_reale'];?></td>
			<td><?php echo $richiesta['localita_gmaps'];?></td>
			<td><?php echo $richiesta['completa'];?></td>
			<td><?php echo $richiesta['lat'];?></td>
			<td><?php echo $richiesta['lon'];?></td>
			<td><?php echo $richiesta['created'];?></td>
			<td><?php echo $richiesta['modified'];?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'richieste', 'action' => 'view', $richiesta['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'richieste', 'action' => 'edit', $richiesta['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'richieste', 'action' => 'delete', $richiesta['id']), null, __('Are you sure you want to delete # %s?', $richiesta['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Richiesta'), array('controller' => 'richieste', 'action' => 'add'));?> </li>
		</ul>
	</div>
</div>
