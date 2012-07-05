<?php /* @var $this DummyView */ ?>
<div class="well sidebar-nav">
    <ul class="nav nav-list">
        <li class="nav-header">Richieste</li>
        <li><?php echo $this->Html->link('Richieste', array('controller' => 'richieste', 'action' => 'index')); ?> </li>
        <li><strong><?php echo $this->Html->link('Aggiungi richiesta', array('controller' => 'richieste', 'action' => 'add')); ?></strong></li>
        <?php echo $this->element('_sidebar_block',array('block_title' => "Richieste per tipo", 'actual_controller' => 'richieste', 'actual_list' => $tipi_list, 'filter_param_name' => 'tipo')) ?>
    </ul>
</div>
<div class="well sidebar-nav">
    <ul class="nav nav-list">
        <li class="nav-header">Offerte</li>
        <li><?php echo $this->Html->link('Offerte', array('controller' => 'offerte', 'action' => 'index')); ?> </li>
        <li><strong><?php echo $this->Html->link('Aggiungi offerta', array('controller' => 'offerte', 'action' => 'add')); ?></strong></li>
        
        <?php echo $this->element('_sidebar_block',array('block_title' => "Offerte per tipo", 'actual_controller' => 'offerte', 'actual_list' => $tipi_list, 'filter_param_name' => 'tipo')) ?>
    </ul>
</div>
<div class="well sidebar-nav">
    <ul class="nav nav-list">
        <li class="nav-header">Province</li>
        <li>Vedi per provincia:</li>
        <li><?php echo $this->Html->link('Qualsiasi', array('action' => 'cambia_provincia', 'all')); ?> </li>
        <?php foreach ($province_list as $pid => $singola_provincia): ?>
            <li><?php echo $this->Html->link($singola_provincia, array('action' => 'cambia_provincia', $pid)); ?> </li>
        <?php endforeach; ?>
    </ul>
</div>
<div class="well sidebar-nav">
    <ul class="nav nav-list">
        <li class="nav-header">Facebook</li>
        <li>
            <?php echo $this->Facebook->friendpile(); ?>
        </li>
        <li>
            <?php echo $this->Facebook->likebox(); ?>
        </li>
    </ul>
</div>