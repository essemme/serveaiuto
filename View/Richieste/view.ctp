<div class="richieste view">
    <?php /* @var $this DummyView */ ?>
<h2><?php  echo __('Richiesta');?></h2>
    <div class="row">
        <div class="span9">
            <p><?php $this->Facebook->sendButton(); echo ' ';  ?> </p>
            <p>Inserito il:
            <?php echo $this->Time->format('d-m-Y H:i', $richiesta['Richiesta']['created']); ?>
        
                    - Ultima modifica: <?php echo $this->Time->format('d-m-Y H:i',$richiesta['Richiesta']['modified']); ?>&nbsp;
                    -
                 <?php 
                    $mostra = $richiesta['User']['username'];
                    if($this->Session->read('Auth.User.role_id') > 1) $mostra = 'Utente numero '. $richiesta['User']['id'];
                    echo $this->Html->link($mostra, array('controller' => 'richieste', 'action' => 'index', $richiesta['User']['id'])); 
                 ?>
            </p>
	</div>
    </div>

<div class="row">
    <div class="span4">
        
        <div>
            <h4><?php echo $richiesta['Tipo']['descrizione']; ?></h4>
            <h3><?php echo __('Cosa Serve'); ?></h3>
            <p>
                <?php 
                if($richiesta['Richiesta']['verificata']) 
                    echo $this->Html->image('verificato.png', array('title' => 'richiesta verificata', 'alt' => 'richiesta verificata'));
                if($richiesta['Richiesta']['in_evidenza']) 
                    echo $this->Html->image('in_evidenza.png', array('title' => 'richiesta in evidenza', 'alt' => 'richiesta in evidenza'));
                    
                echo ' '. nl2br($richiesta['Richiesta']['cosa_serve']); ?>
            </p>
        </div>
        <div>
            <h3>Dove, a chi serve</h3>
            <p><?php echo nl2br($this->Text->autoLink ($richiesta['Richiesta']['dove_a_chi']) ); ?></p>
        </div>

        <div>
            <h3>Altre informazioni</h3>
            <p><?php echo nl2br($this->Text->autoLink ($richiesta['Richiesta']['testo']) ); ?></p>
        </div>

    </div>
    <div class="span4 offset 1">
        
        <div>
            <h3>Recapiti</h3>
            
            <p><?php 
                if(!$limitato) {
                    echo nl2br(h($richiesta['Richiesta']['telefono'])); 
                } else {
                    echo "Non hai i permessi per vedere i recapiti telefonici. <br/>
                        Puoi comunque inviare un messaggio via email compilando il modulo qui sotto";
                }
                ?>
            </p>

            <div>
                <?php echo $this->Form->create('Richiesta', array('url' => '/richieste/invia_messaggio/'.$richiesta['Richiesta']['id']) );?>
     
                <fieldset>
		<legend>Rispondi privatamente</legend>
                    <?php         
                    echo $this->Form->input('id', array('type' => 'hidden', 'value' => $richiesta['Richiesta']['id']));
                    echo $this->Form->input('tua_email', array('value' => $this->Session->read('Auth.User.email')));
                    echo $this->Form->input('testo', array('label' => 'Messaggio. Lascia eventualmente anche un recapito telefonico' ));
                    ?>
                </fieldset>
                <?php echo $this->Form->end(__('Invia'));?>
            </div>

        </div>

    </div>

</div>



<?php if (!empty($offerte_suggerite)): ?>
<div class="row">    
    <div class="span4">
        <?php if($this->Session->read('Auth.User.role_id') < 3) : ?>
                            <?php echo $this->Html->link('Modifica', array('action' => 'edit', $richiesta['Richiesta']['id']), array('class' => 'btn')); ?>
                            <?php echo $this->Html->link('Completa', array('action' => 'completa', $richiesta['Richiesta']['id']), array('class' => 'btn'));  ?>
                            <?php 
                            if($this->Session->read('Auth.User.role_id') < 2) 
                            echo $this->Form->postLink('Elimina', array('action' => 'delete', $richiesta['Richiesta']['id']), array('class' => 'btn'), __('Are you sure you want to delete # %s?', $richiesta['Richiesta']['id'])); 
                            ?>
                 <?php endif; ?>
    </div>
    <div class="span4">
        <p>
            <a class="btn btn-danger" data-toggle="modal" href="#myModal" >Offerte Suggerite</a>
        </p>
    </div>
</div>	

<div class="modal hide" id="myModal" style="width:780px;">
    <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">×</button>
    <h3>Offerte Suggerite</h3>    
    </div>
    <div class="modal-body">
        <p><strong>Attenzione!</strong> Questi sono solo "suggerimenti" selezionati automaticamente in base ai testi 
            ed alle parole chiave specificate. Non c'è garanzia che siano quelli più pertinenti! 
            Se cerchi qualcosa di specifico, puoi sempre usare <a href="/offerte/index">la funzione filtro nell'elenco delle offerte</a>.
        </p>
        
        <?php if(!empty($offerte_top)) : ?>
            <p>Alcune offerte che potrebbero coincidere, in base alle parole chiave specificate ed altri elementi del testo.</p>    
            <?php echo $this->element('offerte_index_snippet', array('offerte' => $offerte_top)) ?>
        <?php else: ?>
            <h4>Non ci sono offerte coincidenti anche per parola chiave. Puoi veder gli altri suggeriemnti, ma sono probabilmente meno rilevanti</h4>
        <?php endif; ?>
        <?php if(!empty($offerte_suggerite)) : ?>
            <p>Altre offerte che potrebbero avere rilevanza. I metodi automatici per determinare la rilevanza in base ai testi non danno garanzie, soprattutto con numeri non altissimi di elementi.
                E' possibile che non c'entrino.. </p>    
            <?php echo $this->element('offerte_index_snippet', array('offerte' => $offerte_suggerite)) ?>
        <?php endif; ?>    
            
        
        
    </div>
    <div class="modal-footer">
    <a href="#" class="btn" data-dismiss="modal">Chiudi</a>
    </div>
</div>
       
   
<?php endif; ?>

    <div class="row">
        <div class="span8">
            <?php 
                        $params[0] = array(
                            'lat' => $richiesta['Richiesta']['lat'],
                            'lon' => $richiesta['Richiesta']['lon'],
                            'title' => $richiesta['Richiesta']['cosa_serve'], 
                            'content' => '<strong>'. h($richiesta['Richiesta']['cosa_serve']).'</strong> <br /> '. h($richiesta['Richiesta']['dove_a_chi']).'<br />',   # optional    
                            'indirizzo_gmaps' =>  $richiesta['Richiesta']['localita_gmaps'],
                            //'icon' => 'http://'. $_SERVER['SERVER_NAME']. '/img/presentation.png'
                        );
                        //debug($params);
                        if(!empty($richiesta['Richiesta']['lat']))
                            echo $this->element('show_map', array('params' => $params));
                        
             ?>
            
        </div>
    </div>

    
                  
</div>

