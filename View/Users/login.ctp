
<?php if(AuthComponent::user('id')) : ?>

<div class="row">
    <div class="span9"> 
        <h3>Sei già autenticato.</h3>
        <p>In questo momento sei autenticato come <?php echo AuthComponent::user('username') ?>.</p>
        <p>
            <?php 
            if(AuthComponent::user('facebook_id') > 0) {
                echo $this->Facebook->logout(array('label' => 'Esci [anche da Facebook]', 'redirect' => '/users/logout')); 
            } else {
                echo $this->Html->link('Esci', '/users/logout'); 
            }
            ?>
        </p>
    </div>
</div>
<?php endif; ?>
    
<div class="row">
    <div class="span4"> 
        <div class="users form">
        <?php echo $this->Form->create('User');?>
                <fieldset>
                        <legend>Accedi</legend>
                <?php
                        echo $this->Form->input('username');
                        echo $this->Form->input('password');
                        //echo $this->Form->input('email');

                ?>
                </fieldset>
        <?php echo $this->Form->end('Accedi');?>
        </div>
        
        <p>Dimenticato la password? Puoi <a class="btn" href="/forgotten_password">reimpostarla</a></p>
        <p>Non sei registrato?  <br /><a class="btn" href="/register">Registrati</a> oppure <span class="btn" style="color: whitesmoke;"><?php echo $this->Facebook->login(array('perms' => 'email,read_stream,publish_stream', 'label'=>'Accedi con Facebook', 'redirect' => '/richieste/index')); ?></span>
    </p>
    </div>
    <div class="span4 offest1">
        <h3>Accedi con Facebook</h3>
        <p>Oppure utilizza il tuo profilo Facebook.</p>
        <p>Puoi utilizzare il tuo profilo facebook come se fosse un utente di questo sito.</p>
        <span class="btn" style="color: whitesmoke;"><?php echo $this->Facebook->login(array('perms' => 'email,read_stream,publish_stream', 'label'=>'FB Accedi', 'redirect' => '/richieste/index')); ?></span>
    </div>
    
</div>