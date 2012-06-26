<table class="table table-bordered table-striped" >
	<tr>
			<th>id <?php //echo $this->Paginator->sort('id');?></th>
			<th>nome <?php //echo $this->Paginator->sort('nome');?></th>			
			<th>tipo<?php //echo $this->Paginator->sort('tipo_id');?></th>
			<th>cosa <?php //echo $this->Paginator->sort('offerta');?></th>
                        <th>Dove</th>
                        <th></th>
			<th class="actions"><?php echo __('Actions');?></th>
	</tr>
	<?php
	foreach ($offerte as $offerta):  
        ?>
        
            <?php 
            
            $dove = '';
            if(!empty($offerta['Provincia'] )) {
                foreach($offerta['Provincia'] as $prov) {               

                        $dove[] = $this->Html->link($prov['provincia'],array('action' => 'index', 'provincia' => $prov['id']));

                }
                $dove = 'Province: ' .implode(', ', $dove);            
            
            
//            if(AuthComponent::user('id')) {            
//                if(AuthComponent::user('role_id') < 3) {
//                    $dove = '<strong>'. h($offerta['Offerta']['dove_a_chi']) . '</strong>. '.  $dove;
//                }
//                
//            // add values to map
//                        $params[] = array(
//                            'lat' => $richiesta['Richiesta']['lat'],
//                            'lon' => $richiesta['Richiesta']['lon'],
//                            'title' => $richiesta['Richiesta']['cosa_serve'], 
//                            'content' => '<strong>'. h($richiesta['Richiesta']['cosa_serve']).'</strong> <br /> '. $dove .'<br />',   # optional    
//                            'indirizzo_gmaps' =>  $richiesta['Richiesta']['localita_gmaps'],
//                            //'icon' => 'http://'. $_SERVER['SERVER_NAME']. '/img/presentation.png'
//                        );
//            }
            }                          
            ?>
        
	<tr>
		<td><?php echo h($offerta['Offerta']['id']); ?>&nbsp;</td>
		<td>
                    
                    <?php if(!$offerta['Offerta']['pubblica']) echo $this->Html->image('privata.png', array('title' => 'offerta riservata. Recapiti visibili solo agli admin, non alle organizzazioni ed altri utenti')); ?>
                    
                    <?php if(
                            AuthComponent::user('role_id') == 1 || 
                            $offerta['Offerta']['pubblica'] || 
                            $offerta['Offerta']['user_id'] == AuthComponent::user('id') 
                            ): ?>
                    <?php echo h($offerta['Offerta']['nome']); ?>&nbsp;
                    
                        <br><?php echo h($offerta['Offerta']['telefono']); ?>                    
                        <br><?php echo h($offerta['Offerta']['email']); ?>
                    <?php else : ?>
                                 Offerta "privata" (i recapiti sono accessibili solo agli admin).
                    <?php endif; ?>
                    <br />
                    <?php echo $this->element('tags_tooltip', array('tags_array' => $offerta['Tag'])); ?>
                </td>
		
		<td>
                    <?php echo $this->Html->link($offerta['Tipo']['nome'], array('controller' => 'offerte', 'action' => 'index', 'tipo' => $offerta['Tipo']['id'])); ?>
                </td>
		<td><?php 
                    if($offerta['Offerta']['verificata']) 
                        echo $this->Html->image('verificato.png', array('title' => 'Offerta verificata', 'alt' => 'Offerta verificata'));
                    if($offerta['Offerta']['in_evidenza']) 
                        echo $this->Html->image('in_evidenza.png', array('title' => 'Offerta in evidenza', 'alt' => 'Offerta in evidenza'));
                    if($offerta['Offerta']['pubblica']) 
                        echo $this->Html->image('org.png', array('title' => 'Offerta visibile alle organizzazioni (non solo agli admin)', 'alt' => 'Offerta visibile alle organizzazioni (non solo agli admin)'));
                    echo ' ';
                    if($offerta['Offerta']['completa']) echo '<strike>';
                    echo nl2br($this->Text->truncate(h($offerta['Offerta']['offerta']))); 
                    if($offerta['Offerta']['completa']) echo '</strike>';
                    
                ?>&nbsp;
                <br /> 
                            [<?php echo $offerta['Categoria']['categoria']; ?>]
                </td>
                <td>
                        <?php if($dove != '') echo $dove; ?>
                    
                </td>
                <td>                    
                    <?php echo $this->element('toggle', array('record_id' => $offerta['Offerta']['id'], 'field'  => 'in_evidenza', 'value' => $offerta['Offerta']['in_evidenza'])) ?>
                    <?php echo $this->element('toggle', array('record_id' => $offerta['Offerta']['id'], 'field'  => 'verificata', 'value' => $offerta['Offerta']['verificata'])) ?>
                    <?php
                    if(AuthComponent::user('role_id') == 1) 
                        echo $this->element('toggle', array('record_id' => $offerta['Offerta']['id'], 'field'  => 'pubblica', 'value' => $offerta['Offerta']['pubblica'], 'label' => 'visibile alle org.')) 
                     ?>
                </td>
		<td class="actions">
			<?php echo $this->Html->link('Esamina', array('controller' => 'offerte', 'action' => 'view', $offerta['Offerta']['id']), array('class' => 'btn btn-mini')); ?>
                    <?php if(AuthComponent::user('role_id') == 1 || AuthComponent::user('id') == $offerta['Offerta']['user_id']): ?>
			<?php echo $this->Html->link('Modifica', array('controller' => 'offerte', 'action' => 'edit', $offerta['Offerta']['id']), array('class' => 'btn btn-mini')); ?>
			<?php echo $this->Html->link('Completa', array('controller' => 'offerte', 'action' => 'completa', $offerta['Offerta']['id']), array('class' => 'btn-mini'));  ?>
                        <?php 
                        if(AuthComponent::user('role_id') == 1 )
                            echo $this->Form->postLink('Elimina', array('controller' => 'offerte', 'action' => 'delete', $offerta['Offerta']['id']), array('class' => 'btn btn-mini'), __('Are you sure you want to delete # %s?', $offerta['Offerta']['id'])); ?>
                    <?php endif; ?>
                </td>
	</tr>
<?php endforeach; ?>
</table>

<script>
$('.tool-tip').tooltip()
</script>