<?php
/**
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       Cake.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

$cakeDescription = __d('cake_dev', 'CakePHP: the rapid development php framework');
?>
<?php /* @var $this DummyView */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php echo $this->Facebook->html(); ?>
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
	ServeAiuto:
		<?php echo $title_for_layout; ?>
	</title>
    
<!--          
            <link rel="stylesheet" href="/css/bootstrap.min.css">
            <link href="/favicon.ico" type="image/x-icon" rel="icon" />
            <link href="/favicon.ico" type="image/x-icon" rel="shortcut icon" />
            <link rel="stylesheet" type="text/css" href="/css/bootstrap.min.css" />
            <link rel="stylesheet" type="text/css" href="/css/ui-lightness/jquery-ui-1.8.19.custom.css" />
            <script type="text/javascript" src="/js/jquery-1.7.2.min.js"></script>
            <script type="text/javascript" src="/js/jquery-ui-1.8.19.custom.min.js"></script>
            <script type="text/javascript" src="/js/bootstrap.min.js"></script>
            <script type="text/javascript" src="/js/cakebootstrap.js"></script>
-->

	<?php
		echo $this->Html->meta('icon');

		echo $this->Html->css('/css/bootstrap.min');
                echo $this->Html->css('/css/ui-lightness/jquery-ui-1.8.19.custom.css');
		echo $this->Html->script('/js/jquery-1.7.2.min.js'); // This MUST go before bootstrap.min.js
                echo $this->Html->script('/js/jquery-ui-1.8.19.custom.min');
		echo $this->Html->script('/js/bootstrap.min.js');
		echo $this->Html->script('cakebootstrap.js');
                
		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
	?>
    
    <!--  UGLY HACK to fix paths.. -->
      
</head>
<body>
    <div class="navbar navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand" href="/">ServeAiuto</a>
          <div class="nav-collapse">
            <ul class="nav">
                <li<?php if($this->request->controller == 'pages') echo ' class="active"'; ?>>
                    <?php echo $this->Html->link(__('Home'), array('controller' => 'pages', 'action' => 'display', 'home')); ?>
                </li>
                <li<?php if($this->request->controller == 'richieste') echo ' class="active"'; ?>>
                    <?php echo $this->Html->link('Richieste', array('controller' => 'richieste', 'action' => 'index')); ?>                
                </li>
                <li<?php if($this->request->controller == 'offerte') echo ' class="active"'; ?>>
                    <?php echo $this->Html->link('Offerte', array('controller' => 'offerte', 'action' => 'index')); ?>                
                </li>
            </ul>
          </div><!--/.nav-collapse -->
          
          
          <?php if(AuthComponent::user('id') && $this->request->controller != 'pages' ): ?>
          <ul class="nav">
              <li class="dropdown"><a href="#"
		          class="dropdown-toggle"
		          data-toggle="dropdown">
		          Province
		          <b class="caret"></b>
		    </a>                      
		    <ul class="dropdown-menu">
                        <li><?php echo $this->Html->link('Qualsiasi', array('action' => 'cambia_provincia', 'all')); ?> </li>
                        <?php foreach ($province_list as $pid => $singola_provincia): ?>
                            <li><?php echo $this->Html->link($singola_provincia, array('action' => 'cambia_provincia', $pid)); ?> </li>
                        <?php endforeach; ?>
                    </ul>
              </li>
          </ul>
          <?php endif; ?>
                
          
		<ul class="nav">
		  <li <?php if($this->request->controller == 'users') echo ' class="dropdown active"'; else echo ' class="dropdown"';?>>
		    <a href="#"
		          class="dropdown-toggle"
		          data-toggle="dropdown">
		          Utente
		          <b class="caret"></b>
		    </a>                      
		    <ul class="dropdown-menu">
                    <!--nocache-->
                        <?php if(AuthComponent::user('id')): ?>
                            <li>
                                <?php 
                                    echo $this->Html->link(AuthComponent::user('username'),'/users/profilo'); 
                                    //echo ' ' .AuthComponent::user('facebook_id'); 
                                ?> 
                            </li>
                            <li>
                                <?php 
                                if(AuthComponent::user('facebook_id') > 0) {
                                    echo $this->Facebook->logout(array('label' => 'Esci [anche da Facebook]', 'redirect' => '/users/logout')); 
                                    echo '</li>
                                        <li>';
                                    echo $this->Html->link('Esci', '/users/logout'); 
                                    '</li>
                                        <li>';
                                    echo $this->Facebook->disconnect(array(
                                            'label' => 'Rimuovi autorizzazione facebook', 
                                            'redirect' => '/users/logout', 
                                            'confirm' => 'Sicuro? In questo modo rimuoverai il legame tra il tuo profilo facebook e questo sito'
                                        )
                                    );
                                }  else {
                                    echo $this->Html->link('Esci', '/users/logout'); 
                                }
                                ?>
                            </li>
                    <!--nocache-->
                    <?php if($this->Session->read('Auth.User.role_id') == 1) : ?>                    
                        <li>
                            <?php echo $this->Html->link('admin: Gestisci Utenti', array('controller' => 'users', 'action' => 'index')); ?> 
                        </li>
                    <?php endif; ?>
                    <!--/nocache-->
                        <?php else: ?>
                            <li><?php echo $this->Html->link('Accedi', array('controller' => 'users', 'action' => 'login')); ?></li>
                            <li><?php echo $this->Html->link('Password dimenticata', array('controller' => 'users', 'action' => 'forgotten_password')); ?></li>
                            <li class="divider"></li>
                            <li><?php echo $this->Html->link(__('Registrati'), array('controller' => 'users', 'action' => 'register')); ?></li>
                        <?php endif; ?>
                    <!--/nocache-->
		    </ul>
		  </li>
		</ul>
        </div>
      </div>
    </div>
	

    <div id="container" class="container">
                       
                
		<div id="content" class="content">
           
                    <div class="row">
                        <div class="span12">
                            <!--nocache-->
                            <?php 
                            echo $this->Session->flash(); 
                            echo $this->Session->flash('auth'); 
                            ?>
                            <!--/nocache-->
                        </div>
                    </div>
                    <?php 
                    /*
                     * show sidebar if not (home)page
                     */
                    if($this->request->params['controller'] != 'pages'): 
                    ?>
                    <div class="row">
                        <div class="span3">
                            <?php echo $this->element('sidebar', compact('types_list'), array('cache' => '+1 second')); ?>
                        </div>
                        <div class="span9">
                            <?php echo $this->fetch('content'); ?>
                        </div>
                     </div>
                    <?php else: ?>
                    <div class="row">
                        <div class="span12">
                            <?php echo $this->Session->flash(); ?>
                            
                            <?php echo $this->fetch('content'); ?>
                        </div>
                    </div>
                    <?php  endif; ?>
                </div>
		
            </div>
        <div id="footer">
                <?php echo $this->Html->link(
                                $this->Html->image('/img/cake.power.gif', array('alt' => $cakeDescription, 'border' => '0')),
                                'http://www.cakephp.org/',
                                array('target' => '_blank', 'escape' => false)
                        );
                ?>            
            <p>Un instant project di <a href="http://ferrarasociale.org">Agire Sociale CSV Ferrara</a> - in Collaborazione con <a href="http://www.volontariao.it">CSV Modena</a>, <a href="http://www.darvoce.org">Reggio Emilia</a>, <a href="http://www.volabo.it">Bologna</a>, <a href="http://wwww.csvm.it">Mantova</a></p>
            <p>Progetto di <a href="http://stefanomanfredini.info">SM</a> con il decisivo contributo di <a href="http://www.davidebellettini.com">Hackathon Terremoto</a>, si ringraziano in particolare per il prezioso contributo: <a href="http://iliasbartolini.dyndns.org/~brain/">Davide bellettini</a>, Dario Bottazzi, e la partecipazione di <a href="http://iliasbartolini.dyndns.org/~brain/">Ilias Bartolini</a></p>
        </div>
   
    <?php //echo $this->element('sql_dump'); ?>
    <?php echo $this->Facebook->init(); ?>
</body>
</html>
