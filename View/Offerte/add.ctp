<div class="offerte form">
<?php echo $this->Form->create('Offerta');?>
	<fieldset>
		<legend>Aggiungi Offerta / disponibilità</legend>
                 <div class="row"> 
                    <div class="span4">
	<?php
		echo $this->Form->input('nome');
		echo $this->Form->input('user_id', array('type' => 'hidden', 'value' => $this->Session->read('Auth.User.id')));
                    
		echo $this->Form->input('tipo_id');
		
		echo $this->Form->input('telefono');
		echo $this->Form->input('email');
                
                if(AuthComponent::user('role_id') < 3 ) {
                    echo $this->Form->input('verificata');
                    echo $this->Form->input('in_evidenza');                
                    echo $this->Form->input('pubblica');
                    echo $this->Form->input('completa');
                }
                
	?>
                    </div>
                     <div class="span4">
                         <?php
		
                    echo $this->Form->input('offerta');
                    echo $this->Form->input('Provincia', array('multiple' => 'checkbox'));
	?>
                     </div>
                </div>
        </fieldset>
<?php echo $this->Form->end('Salva');?>
</div>

