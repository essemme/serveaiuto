<div class="offerte form">
<?php echo $this->Form->create('Offerta');?>
	<fieldset>
		<legend>Modifica Offerta / disponibilità</legend>
	
                <div class="row"> 
                    <div class="span4">
	<?php
                echo $this->Form->input('id');
		echo $this->Form->input('nome');
		echo $this->Form->input('user_id', array('type' => 'hidden', 'value' => $this->Session->read('Auth.User.id')));
                    
		echo $this->Form->input('tipo_id');
                echo $this->Form->input('categoria_id', array('empty' => '...'));
		
		echo $this->Form->input('telefono');
		echo $this->Form->input('email');
                
                echo $this->element('multiple_autocomplete_snippet',array('field' => 'OffertaTaglist'));
                echo '<div class="ui-widget">';
                echo $this->Html->image('tag_parole_chiave.png',array('style' => 'float:left; padding-right:4px;'));
                echo $this->Form->input('taglist', array('label' => 'parole chiave'));
                echo '</div>';
                
                if(AuthComponent::user('role_id') < 3 ) {
                    
                                                    
                    if(AuthComponent::user('role_id') == 1 ) {
                        echo $this->Html->image('verificata.png', array('style' => 'float:left; margin: 3px;'));
                        echo $this->Form->input('verificata');

                        echo $this->Html->image('in_evidenza.png', array('style' => 'float:left; margin: 3px;'));
                        echo $this->Form->input('in_evidenza'); 
                        
                        echo $this->Html->image('org.png', array('style' => 'float:left; margin: 3px;'));
                        echo $this->Form->input('pubblica', array(
                            'label' => 'visibile anche alle organizzazioni (non solo agli ammnistratori)')
                        );                        
                    }
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
<?php echo $this->Form->end(__('Submit'));?>
</div>