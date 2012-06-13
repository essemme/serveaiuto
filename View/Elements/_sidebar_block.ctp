            <li class="nav-header"><?php echo $block_title; ?></li>            
            <?php 
                foreach ($actual_list as $key => $value) :
            ?>
                <li><?php echo $this->Html->link($value, array('controller' => $actual_controller, 'action' => 'index', $filter_param_name => $key)); ?></li>		
            <?php endforeach; ?>