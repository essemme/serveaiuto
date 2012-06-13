<?php

$config['SignMeUp'] = array(
	'from' => 'serveaiuto.org <info@csvferrara.info>',
	'layout' => 'default',
	'welcome_subject' => 'Benvenuto, %username%!',
	'activation_subject' => 'Attiva il tuo profilo, %username%!',
	'sendAs' => 'text',
	'activation_template' => 'activate',
	'welcome_template' => 'welcome',
    
	'password_reset_field' => 'password_reset',
        'activation_field' => 'activation_code',
	'useractive_field' => 'active',
    
	'password_reset_template' => 'forgotten_password',
	'password_reset_subject' => 'Reimposta password',
	'new_password_template' => 'new_password',
	'new_password_subject' => 'La tua nuova password',
	'xMailer' => 'php mailer',
);