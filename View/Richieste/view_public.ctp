<div class="richieste view">
<h2><?php  echo __('Richiesta');?></h2>
<div class="row">
        <div class="span9">
            <p><?php $this->Facebook->sendButton(); echo ' ';  ?> <?php $this->Facebook->share(); echo ' ';  ?></p>
            <p>Inserito il:
            <?php echo $this->Time->format('d-m-Y H:i', $richiesta['Richiesta']['created']); ?>        
                    - Ultima modifica: <?php echo $this->Time->format('d-m-Y H:i',$richiesta['Richiesta']['modified']); ?>&nbsp;
                    -
                 <?php 
                    
                    $mostra = 'Utente numero '. $richiesta['User']['id'];
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

                echo ' '.nl2br(h($richiesta['Richiesta']['cosa_serve'])); 
                ?>
            </p>
        </div>
        <div>
            <h3>Riferimenti per informazioni</h3>
            <p>Devi registrarti per poter vedere maggiorni dettagli. Altriemtni puoi contattare l'organizzazione di riferimento che può fare da tramite per la provincia specifica</p>
            <?php foreach ($richiesta['Provincia'] as $key => $prov) : ?>
                <h4><?php echo nl2br(h($prov['provincia'])); ?></h4>
                <p><?php echo nl2br($this->Text->autoLink(h($prov['riferimenti']))); ?></p>
            <?php endforeach; ?>
        </div>

        

    </div>
    <div class="span4 offset 1">
        
        <div>
            <h3>Dettagli della richiesta</h3>
            
            <p>Per vedere il testo completo, i dettagli, i recpiti, e poter rispondere tramite il modulo sul sito, devi essere registrato ed accedere.</p> 
            <p>
                <a class="btn" href="/login">Entra</a> (anche usando facebook) <br />
                <p>Non sei registrato?  <br /><a class="btn" href="/register">Registrati</a> oppure <span class="btn" style="color: whitesmoke;"><?php echo $this->Facebook->login(array('perms' => 'email,read_stream,publish_stream', 'label'=>'Accedi con Facebook', 'redirect' => '/richieste/index')); ?></span>
            </p>
        </div>

    </div>

</div>


    <div class="row">
        <div class="span8">
            <?php 
                        $params[0] = array(
                            'lat' => $richiesta['Richiesta']['lat'],
                            'lon' => $richiesta['Richiesta']['lon'],
                            'title' => $richiesta['Richiesta']['cosa_serve'], 
                            'content' => '<strong>'. h($richiesta['Richiesta']['cosa_serve']), //.'</strong> <br /> '. h($richiesta['Richiesta']['dove_a_chi']).'<br />',   # optional    
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

