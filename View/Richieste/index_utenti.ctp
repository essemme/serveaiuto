<div class="richieste index">
    <div class="row"> 
        <div class="span6">
            <h2><?php echo __('Richieste');?></h2>
            <p> Puoi selezionare solo le richieste incomplete, oppure vedere solo quelle già completate, o tutte</p>
            <p>
                <a class="btn" href="/richieste/index/completa:all">Vedi tutte le richieste</a>
                <a class="btn" href="/richieste/index/completa:0">Richieste ancora attive</a>
                <a class="btn" href="/richieste/index/completa:1">Richieste completate</a>
            </p>   
        </div>
        <div class="span3">
            <?php echo $this->Filter->filterForm('Richiesta', array('legend' => 'Filtra'));  ?>
        </div>
    </div>
        
        
	<table class="table table-bordered table-striped">
	<tr>
			<th>Id<?php //echo $this->Paginator->sort('id');?></th>
                        <th>Tipo<?php //echo $this->Paginator->sort('tipo_id');?>
                            <br/>scadenza indicativa<?php //echo $this->Paginator->sort('scadenza', 'scadenza indicativa');?></th>
			<th>Cosa serve<?php //echo $this->Paginator->sort('cosa_serve');?></th>			
			<th>Dove, a chi<?php //echo $this->Paginator->sort('dove_a_chi', 'dove, a chi');?></th>			
                            <?php if($this->Session->read('Auth.User.role_id') < 3 ) : ?>
                        <th>Inserimento, <?php //echo $this->Paginator->sort('created', 'inserimento');?><br/>
                            Ultima modifica<?php //echo $this->Paginator->sort('modified', 'ultima modifica');?>, inserito da<?php //echo $this->Paginator->sort('user_id');?>
                        </th>
                        <th></th>
                           
			<?php endif; ?>
			<th class="actions">Azioni</th>
	</tr>
	<?php
	foreach ($richieste as $richiesta): ?>
       
            <?php 
            $dove = '';
            
            foreach($richiesta['Provincia'] as $prov) {               
                    
                    $dove[] = $this->Html->link($prov['provincia'],array('action' => 'index', 'provincia' => $prov['id']));
                
            }
            $dove = 'Province: ' .implode(', ', $dove);            
            
            
            if(AuthComponent::user('id')) {            
                if(AuthComponent::user('role_id') < 3) {
                    $dove = '<strong>'. h($richiesta['Richiesta']['dove_a_chi']) . '</strong>. '.  $dove;
                }
                
            // add values to map
                        $params[] = array(
                            'lat' => $richiesta['Richiesta']['lat'],
                            'lon' => $richiesta['Richiesta']['lon'],
                            'title' => $richiesta['Richiesta']['cosa_serve'], 
                            'content' => '<strong>'. h($richiesta['Richiesta']['cosa_serve']).'</strong> <br /> '. $dove .'<br />',   # optional    
                            'indirizzo_gmaps' =>  $richiesta['Richiesta']['localita_gmaps'],
                            //'icon' => 'http://'. $_SERVER['SERVER_NAME']. '/img/presentation.png'
                        );
            }
                                               
             ?>
            
	<tr>
		<td>
                    <?php echo h($richiesta['Richiesta']['id']); ?>
                    <br />
                        <?php 
                    if($richiesta['Richiesta']['verificata']) 
                        echo $this->Html->image('verificato.png', array('title' => 'richiesta verificata', 'alt' => 'richiesta verificata'));
                    if($richiesta['Richiesta']['in_evidenza']) 
                        echo $this->Html->image('in_evidenza.png', array('title' => 'richiesta in evidenza', 'alt' => 'richiesta in evidenza'));
                    if($richiesta['Richiesta']['pubblica']) 
                        echo $this->Html->image('pubblica.png', array('title' => 'richiesta pubblica (visibile con dettagli a utenti registrati)', 'alt' => 'richiesta pubblica (visibile con dettagli a utenti registrati)'));
                     if($richiesta['Richiesta']['segnala_in_indice_sito']) 
                        echo $this->Html->image('segnala.png', array('title' => 'richiesta esportabile (il "cosa" appare nella lista di necessità per provincia che può essere visualizzata su altri siti)', 'alt' => 'richiesta esportabile (il "cosa" appare nella lista di necessità per provincia che può essere visualizzata su altri siti)'));
                    ?>
                
                </td>
                <td>
                    <?php echo $this->Html->link($richiesta['Tipo']['nome'], array('controller' => 'richieste', 'action' => 'index', 'tipo' => $richiesta['Tipo']['id'])); ?>
                    <br/>
                    
                        <?php echo $this->element('tags_tooltip', array('tags_array' => $richiesta['Tag'])); ?>
                    
                    <?php
                    if (!is_null($richiesta['Richiesta']['scadenza'])){                                        
                        echo 'indic. entro il:'.  $this->Time->format('d-m-Y',$richiesta['Richiesta']['scadenza']); 
                        if( $richiesta['Richiesta']['scadenza'] < date('Y-m-d') ) {
                            echo '<span class="btn btn-warning">';
                            echo $this->Html->image('icons/error.png');
                            echo $this->Html->image('icons/date.png');
                            echo ' probabilmente scaduta</span>';
                        }
                    }
                    ?>
                </td>
		<td>
                    <strong>
                    <?php 
                    
                    if($richiesta['Richiesta']['completa']) {
                        echo '<strike>'. h($richiesta['Richiesta']['cosa_serve']).'</strike>'; 
                    } else {
                        echo h($richiesta['Richiesta']['cosa_serve']); 
                    }
                    
                    
                    ?>
                    </strong>
                    <br /> 
                            [<?php echo $richiesta['Categoria']['categoria']; ?>]
                </td>
		
		<td>
                    <?php 
                   
                    echo h($richiesta['Richiesta']['dove_a_chi']); 
                                        
                    ?>
                    
                    
                    <?php if($this->Session->read('Auth.User.role_id') == 1): ?>
                        <?php echo h($richiesta['Richiesta']['telefono']); ?>
                        <?php echo h($richiesta['Richiesta']['email']); ?> 
                    <?php endif; ?>
                </td>
		
                <?php if($this->Session->read('Auth.User.role_id') < 3 ) : ?>
		<td><?php echo $this->Time->format('d-m-Y H:i', $richiesta['Richiesta']['created']); ?>&nbsp;
                    <br/><?php echo $this->Time->format('d-m-Y H:i', $richiesta['Richiesta']['modified']); ?>&nbsp;
                    <br/>
                 <?php
                    $mostra = $richiesta['User']['username'];
                    if($this->Session->read('Auth.User.role_id') > 1) $mostra = 'Utente numero '. $richiesta['User']['id'];
                    echo $this->Html->link($mostra, array('controller' => 'richieste', 'action' => 'index', $richiesta['User']['id'])); ?>
		</td>
                <td>                    
                    <?php echo $this->element('toggle', array('record_id' => $richiesta['Richiesta']['id'], 'field'  => 'in_evidenza', 'value' => $richiesta['Richiesta']['in_evidenza'])) ?>
                    <?php echo $this->element('toggle', array('record_id' => $richiesta['Richiesta']['id'], 'field'  => 'verificata', 'value' => $richiesta['Richiesta']['verificata'])) ?>
                    <?php echo $this->element('toggle', array('record_id' => $richiesta['Richiesta']['id'], 'field'  => 'pubblica', 'value' => $richiesta['Richiesta']['pubblica'])) ?>
                </td>
                <?php endif; ?>	
                
		<td class="actions">
			<?php echo $this->Html->link('Esamina', array('action' => 'view', $richiesta['Richiesta']['id']), array('class' => 'btn btn-mini')); ?>
                        <?php if(AuthComponent::user('role_id') < 3) : ?>
                            <?php if(AuthComponent::user('role_id') < 2 || AuthComponent::user('id') == $richiesta['Richiesta']['user_id'] ) : ?>
                                <?php echo $this->Html->link('Modifica', array('action' => 'edit', $richiesta['Richiesta']['id']), array('class' => 'btn btn-mini')); ?>
                                <?php echo $this->Html->link('Completa', array('action' => 'completa', $richiesta['Richiesta']['id']), array('class' => 'btn btn-mini'));  ?>
                            <?php endif; ?>
                            <?php 
                            if(AuthComponent::user('role_id') < 2) 
                            echo $this->Form->postLink('Elimina', array('action' => 'delete', $richiesta['Richiesta']['id']), array('class' => 'btn btn-mini'), __('Are you sure you want to delete # %s?', $richiesta['Richiesta']['id'])); 
                            ?>
                            
                        <?php endif; ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
        
        
	<p>
	<?php
//	echo $this->Paginator->counter(array(
//	'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
//	));
	?>	
        </p>

	<div class="pagination">
	<?php
		echo $this->Paginator->prev('<< ' , array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ' '));
		echo $this->Paginator->next(' >>', array(), null, array('class' => 'next disabled'));
	?>
	</div>
        
        <?php if(!empty($params)): ?>
        <div class="row">
            <div class="span8">
            <?php 
                        
                        //debug($params);
                        if(!empty($params))
                            echo $this->element('show_map', array('params' => $params));
                        
             ?>
            
            </div>            
        </div>
        <?php endif; ?>
</div>


<script>
$('.tool-tip').tooltip()
</script>