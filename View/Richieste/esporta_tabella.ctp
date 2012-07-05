<div class="richieste index">
	
        
	<table class="table table-bordered table-striped" >
	<tr>
			
                        <th>Categoria<?php //echo $this->Paginator->sort('tipo_id');?>
                            </th>
			<th>Cosa serve<?php //echo $this->Paginator->sort('cosa_serve');?></th>			
                        <?php 
                        if($richieste[0]['ProvinceRichieste']['Provincia']['aperta']) {
                            echo '<th>';
                            echo '</th>';                        
                        }
                        ?>
                       
	</tr>
	<?php
        $richiesta_precedente = 0;
	foreach ($richieste as $richiesta): 
        ?>
        
	<tr>		
                <td>
                    <?php 
                    
                    if($richiesta['Categoria']['id'] != $richiesta_precedente) {
                        //echo $this->Html->link($richiesta['Tipo']['nome'], array('controller' => 'richieste', 'action' => 'index', 'tipo' => $richiesta['Tipo']['id'])); 
                        echo "<strong>". $richiesta['Categoria']['categoria'] . "</strong>";
                    }
                    
                    $richiesta_precedente = $richiesta['Categoria']['id']; 
                    ?>
                    <br/><?php
//                    if (!is_null($richiesta['Richiesta']['scadenza'])){                                        
//                        echo 'indic. entro il:'.  $this->Time->format('d-m-Y',$richiesta['Richiesta']['scadenza']); 
//                        if( $richiesta['Richiesta']['scadenza'] < date('Y-m-d') ) {
//                            echo '<span class="btn btn-warning">';
//                            echo $this->Html->image('icons/error.png');
//                            echo $this->Html->image('icons/date.png');
//                            echo ' probabilmente scaduta</span>';
//                        }        
//                    }
                    ?>
                </td>
		<td>
                    <?php 
//                    if($richiesta['Richiesta']['verificata']) 
//                        echo $this->Html->image('verificato.png', array('title' => 'richiesta verificata', 'alt' => 'richiesta verificata')) . ' ';
//                    if($richiesta['Richiesta']['in_evidenza']) 
//                        echo $this->Html->image('in_evidenza.png', array('title' => 'richiesta in evidenza', 'alt' => 'richiesta in evidenza'));
//                    if($richiesta['Richiesta']['pubblica']) 
//                        echo $this->Html->image('pubblica.png', array('title' => 'richiesta pubblica (visibile con dettagli a utenti registrati)', 'alt' => 'richiesta pubblica (visibile con dettagli a utenti registrati)'));
//                    
//                    
//                    if($richiesta['Richiesta']['completa']) {
//                        echo '<strike>'. h($richiesta['Richiesta']['cosa_serve']).'</strike>'; 
//                    } else {
                        echo h($richiesta['Richiesta']['cosa_serve']); 
                   //}
                    
                    ?>
                    
                    
                
                </td>
                
                    <?php 
                    if($richieste[0]['ProvinceRichieste']['Provincia']['aperta']) {
                        echo '<td>';
                        echo $this->Html->link('Esamina', array('action' => 'view_public', $richiesta['Richiesta']['id']), array('target' => '_blank', 'class' => 'btn')); 
                        echo '</td>';                        
                    }
                    ?>
                
                
	</tr>
<?php endforeach; ?>
	</table>
        
    <h3>
        Per informazioni
    </h3>
    <p>
        <?php echo nl2br($this->Text->autoLink( h($richieste[0]['ProvinceRichieste']['Provincia']['riferimenti']))); ?>
    </p>
    
    
</div>

