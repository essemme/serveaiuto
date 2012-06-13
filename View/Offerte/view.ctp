<div class="offerte view">
<h2><?php  echo __('Offerta');?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($offerta['Offerta']['id']); ?>
			&nbsp;
		</dd>
                
                <?php if($offerta_privata): ?>
                <dt><?php echo __('Nome'); ?></dt>
                    <dd>
                        <?php if(!$offerta['Offerta']['pubblica']) echo $this->Html->image('privata.png', array('title' => 'offerta riservata. Recapiti visibili solo agli admin, non alle organizzazioni ed altri utenti')); ?>
                        Offerta riservata. Contatta il CSV di riferimento. 
                    </dd>
                <?php else: ?>    
                    <dt><?php echo __('Nome'); ?></dt>
                    <dd>
                            <?php echo h($offerta['Offerta']['nome']); ?>
                            &nbsp;
                    </dd>                    
		<?php endif; ?>
                
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
                
                
                <?php if($offerta_privata): ?>
                <dt>Recapiti per la provincia di Riferimento (dell'utente che ha inserito l'offerta)</dt>
                <dd>
		<?php if(!$offerta['Offerta']['pubblica'])
                    echo $this->Html->image('privata.png', array('title' => 'offerta riservata. Recapiti visibili solo agli admin, non alle organizzazioni ed altri utenti')); ?>
                	<?php echo nl2br($this->Text->autoLink(h($riferimenti['Provincia']['riferimenti']))); ?>
			&nbsp;
		</dd>
                
                <?php else: ?>
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
                
                <?php endif; ?>
                
	</dl>
</div>