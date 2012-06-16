<html>
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
	?></head>
<body>

    <div id="container" class="container">
        <div id="content" class="content">
            <?php echo $content_for_layout; ?>
        </div>
    </div>
</body>
</html>