Benvenuto <?php echo $user['username']; ?>,

Clicca sul link per attivare il profilo:
<?php echo Router::url(array('action' => 'activate', 'activation_code' => $user['activation_code']), true)."\n"; ?>

OPPURE:

Codica di attivazione (manuale):
<?php echo $user['activation_code']; ?>

Da inserire all'indirizzo:

<?php echo Router::url(array('action' => 'activate'), true); ?>

Grazie!
