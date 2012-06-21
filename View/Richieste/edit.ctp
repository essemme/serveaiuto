<div class="richieste form">
<?php echo $this->Form->create('Richiesta');?>
     <script type="text/javascript">
            $(function(){
                    // Datepicker
                    $('.datepicker').datepicker({
                            inline: true,
                            dateFormat: 'yy-mm-dd'
                    });
            });
    </script>
	<fieldset>
		<legend><?php echo __('Edit Richiesta'); ?></legend>
                
                <div class="row"> 
                    <div class="span4">
                        <h4>Dati richiesta</h4>
                    <?php
                    echo $this->Form->input('id');
                    echo $this->Form->input('cosa_serve', array('label' => 'cosa serve: es. una stampante per la modulistica.. alimentari non deperibili.. volontari per animazione con bambini..' ));
                    echo $this->Form->input('tipo_id');
                    echo $this->Form->input('categoria_id', array('empty' => '...'));
                    echo $this->Form->input('dove_a_chi', array('label' => 'dove, a chi: es. associazione XY presso campo protezione civile di..' ));
                    echo $this->Form->input('testo', array('label' => 'altre informazioni. Visibili solo agli utenti registrati' ));
                    echo $this->Form->input('telefono',array('label' => 'telefono per contatti. NON mettere il cellulare di altre persone se non esplicitamente autorizzato! ' ) );
                    echo $this->Form->input('email', array('label' => 'email per risposta tramite il modulo sul sito (NON viene visualizzata pubblicamente)' ));
                    echo $this->Form->input('user_id', array('type' => 'hidden', 'value' => $this->Session->read('Auth.User.id')));
                    
                    echo $this->element('multiple_autocomplete_snippet',array('field' => 'RichiestaTaglist'));
                    echo '<div class="ui-widget">';
                    echo $this->Html->image('tag_parole_chiave.png', array('style' => 'float:left; padding-right:4px;') );
                    echo $this->Form->input('taglist', array('label' => 'parole chiave'));
                    echo '</div>';
                    
                    if($this->Session->read('Auth.User.role_id') < 3) {
                        
                        //echo $this->Form->input('attiva');
                        
                        if($this->Session->read('Auth.User.role_id') < 2) {
                            
                            echo $this->Html->image('in_evidenza.png', array('style' => 'float:left; margin: 3px;'));
                            echo $this->Form->input('in_evidenza');
                            
                            echo $this->Html->image('verificata.png', array('style' => 'float:left; margin: 3px;'));
                            echo $this->Form->input('verificata');
                            
                            echo $this->Html->image('pubblica.png', array('style' => 'float:left; margin: 3px;'));
                            echo $this->Form->input('pubblica');
                                                        
                            echo $this->Html->image('segnala.png', array('style' => 'float:left; margin: 3px;'));
                            echo $this->Form->input('segnala_in_indice_sito');
                        }
                        echo $this->Form->input('completa');
                    }
                    
                    
                    
                    ?>
                    </div>
                
                    <div class="span4 offset1">
                        <h4>Opzionali</h4>    
                    <?php
                    
                    echo $this->Form->input('scadenza', array('type' => 'text', 'label' => '[opzionale] scadenza indicativa', 'class' => 'datepicker' ));
                   
                    echo $this->Form->input('localita_gmaps');
                    echo $this->AddressFinder->render();
                    echo $this->Form->input('lat', array('type' => 'hidden'));
                    echo $this->Form->input('lon', array('type' => 'hidden'));
                    echo $this->Form->input('indirizzo_reale', array('label' => 'indirizzo reale, se diverso da quello per piazzare il segnalino sulla mappa'));
                    
                    echo $this->Form->input('Provincia', array('multiple' => 'checkbox'));
                    ?>
                
                    </div>
                </div>
                <div class="row"> 
                    <div class="span8">
                    
                    </div>
                </div>
	
	</fieldset>
<?php echo $this->Form->end(__('Salva'));?>
</div>
