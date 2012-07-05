<div class="richieste index">
	
        
	<table class="table table-bordered table-striped" >
	<tr>
			
                        <th>Tipo<?php //echo $this->Paginator->sort('tipo_id');?>
                            </th>
			<th>Cosa serve<?php //echo $this->Paginator->sort('cosa_serve');?></th>			
			<th>Province di riferiemnto<?php //echo $this->Paginator->sort('dove_a_chi', 'dove, a chi');?></th>			
                        <th> </th>
	</tr>
	<?php
	foreach ($richieste as $richiesta): ?>
       
            <?php 
                        $params[] = array(
                            'lat' => $richiesta['Richiesta']['lat'],
                            'lon' => $richiesta['Richiesta']['lon'],
                            'title' => $richiesta['Richiesta']['cosa_serve'], 
                            'content' => '<strong>'. h($richiesta['Richiesta']['cosa_serve']).'</strong> <br /> '. h($richiesta['Richiesta']['dove_a_chi']).'<br />',   # optional    
                            'indirizzo_gmaps' =>  $richiesta['Richiesta']['localita_gmaps'],
                            //'icon' => 'http://'. $_SERVER['SERVER_NAME']. '/img/presentation.png'
                        );
                                               
             ?>
            
	<tr>
		
                <td>
                    
                
                    <?php echo $this->Html->link($richiesta['Tipo']['nome'], array('controller' => 'richieste', 'action' => 'index', 'tipo' => $richiesta['Tipo']['id'])); ?>
                    
                    
                    <br/><?php
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
                    <?php 
                    
                    if($richiesta['Richiesta']['completa']) {
                        echo '<strike>'. h($richiesta['Richiesta']['cosa_serve']).'</strike>'; 
                    } else {
                        echo h($richiesta['Richiesta']['cosa_serve']); 
                    }
                    
                    ?>
                </td>
		
		<td>
                    <?php 
                    $dove = '';

                    foreach($richiesta['Provincia'] as $prov) {               

                            $dove[] = $prov['provincia'];

                    }
                    $dove = 'Province: ' .implode(', ', $dove);            

                    echo $dove;

                    ?>
                    <?php 
                    
                    if($richiesta['Richiesta']['pubblica'])                        
                    echo '<br />'. h($richiesta['Richiesta']['dove_a_chi']);
                    ?>
                    <br/>
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
                    
                    
                    <?php if($this->Session->read('Auth.User.role_id') == 1): ?>
                        <?php echo h($richiesta['Richiesta']['telefono']); ?>
                        <?php echo h($richiesta['Richiesta']['email']); ?> 
                    <?php endif; ?>
                </td>
		<td class="actions">
			<?php echo $this->Html->link('Esamina', array('action' => 'view_public', $richiesta['Richiesta']['id'])); ?>
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
//                        if(!empty($params))
//                            echo $this->element('show_map', array('params' => $params));
                        
             ?>
            
            </div>            
        </div>
        <?php endif; ?>
</div>

