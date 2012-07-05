<div class="offerte index">
    <div class="row"> 
        <div class="span6">
    <h2><?php if(AuthComponent::user('role_id') > 2) echo 'Le tue ';   echo __('Offerte');?></h2>
        <p>
            <a class="btn" href="/offerte/index/completa:all">Vedi tutte le offerte</a>
            <a class="btn" href="/offerte/index/completa:0">Offerte ancora attive</a>
            <a class="btn" href="/offerte/index/completa:1">Offerte completate</a>
        </p> 
        <p>
            <p>
                <?php echo $this->Html->link('Aggiungi nuova offerta', array('controller' => 'offerte', 'action' => 'add'), array('class'=>"btn btn-info")); ?>

            </p>
        </p>
        </div>
        <div class="span3">
            <?php echo $this->Filter->filterForm('Offerta', array('legend' => 'Filtra'));  ?>
        </div>
    </div>
	<table class="table table-bordered table-striped" >
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('nome');?></th>			
			<th><?php echo $this->Paginator->sort('tipo_id');?></th>
			<th><?php echo $this->Paginator->sort('offerta');?></th>
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
		<td>
                    <?php echo h($offerta['Offerta']['id']); ?>
                    <br />
                    <?php 
                    if($offerta['Offerta']['verificata']) 
                        echo $this->Html->image('verificato.png', array('title' => 'Offerta verificata', 'alt' => 'Offerta verificata'));
                    if($offerta['Offerta']['in_evidenza']) 
                        echo $this->Html->image('in_evidenza.png', array('title' => 'Offerta in evidenza', 'alt' => 'Offerta in evidenza'));
                    if($offerta['Offerta']['pubblica']) 
                        echo $this->Html->image('org.png', array('title' => 'Offerta visibile alle organizzazioni (non solo agli admin)', 'alt' => 'Offerta visibile alle organizzazioni (non solo agli admin)'));
                    
                    //echo ' ';
                    ?>
                    <?php 
                    if(!$offerta['Offerta']['pubblica']) 
                        echo $this->Html->image('privata.png', array('title' => 'offerta riservata. Recapiti visibili solo agli admin, non alle organizzazioni ed altri utenti')); 
                    ?>
                    
                </td>
                
                
		<td>
                    
                    
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
		<td>
                    <?php 
                    
                    if($offerta['Offerta']['completa']) echo '<strike>';
                    echo nl2br($this->Text->truncate(h($offerta['Offerta']['offerta']))); 
                    if($offerta['Offerta']['completa']) echo '</strike>';
                    
                    ?>
                    <br /> 

                    [<?php  echo $this->Html->link( $offerta['Categoria']['categoria'], array('controller' => 'offerte', 'action' => 'index', 'categoria' => $offerta['Categoria']['id'])); ?>]
                     
                           
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
			<?php echo $this->Html->link('Esamina', array('action' => 'view', $offerta['Offerta']['id']), array('class' => 'btn btn-mini')); ?>
                    <?php if(AuthComponent::user('role_id') == 1 || AuthComponent::user('id') == $offerta['Offerta']['user_id']): ?>
			<?php echo $this->Html->link('Modifica', array('action' => 'edit', $offerta['Offerta']['id']), array('class' => 'btn btn-mini')); ?>
			<?php echo $this->Html->link('Completa', array('action' => 'completa', $offerta['Offerta']['id']), array('class' => 'btn-mini'));  ?>
                        <?php 
                        if(AuthComponent::user('role_id') == 1 )
                            echo $this->Form->postLink('Elimina', array('action' => 'delete', $offerta['Offerta']['id']), array('class' => 'btn btn-mini'), __('Are you sure you want to delete # %s?', $offerta['Offerta']['id'])); ?>
                    <?php endif; ?>
                </td>
	</tr>
<?php endforeach; ?>
	</table>
    
	<p>
            <?php if(empty($offerte)): ?>
            <strong>Non ci sono tue offerte al momento. Puoi inserirne una cliccando su "Nuova offerta" nella bassa a sisnistra. 
                Per favore, segnala solo offerte (di beni, tempo, strumenti) che puoi realmente realizzare (o, se offerte di terzi, che siano verificate)
                <br />Per segnalare una disponibilità generica di volontariato nei prossimi mesi controlla anche sul sito
                <a href="http://terremoto.volontariamo.com/">http://terremoto.volontariamo.com/</a>
            </strong>
            <?php endif; ?>
            
	<?php
//	echo $this->Paginator->counter(array(
//	'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
//	));
	?>	
        </p>

	<div class="pagination">
	<?php
		echo $this->Paginator->prev('<< ' , array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next( ' >>', array(), null, array('class' => 'next disabled'));
	?>
	</div>
</div>


<script>
$('.tool-tip').tooltip()
</script>