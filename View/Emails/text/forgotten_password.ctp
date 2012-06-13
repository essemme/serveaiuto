Salve <?php echo $user['username']; ?>,

Qualcuno (si spera tu stesso) ha richiesto di reimpostare la tua password sul sito. 

Per scegliere una nuova password, clicca sul link seguente:
<?php echo $this->Html->link('Reset your password', Router::url(array('action' => 'forgotten_password', 'password_reset' => $user['password_reset']), true)); ?>

Altriemnti, ignora questo messaggio.
