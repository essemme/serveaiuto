            <li class="nav-header"> <span style="padding-left: 12px;"><?php echo $block_title; ?></span></li>            
            <?php 
                foreach ($actual_list as $key => $value) :
            ?>
                <li style="padding-left: 12px;"><?php echo $this->Html->link($value, array('controller' => $actual_controller, 'action' => 'index', $filter_param_name => $key)); ?></li>		
            <?php endforeach; ?>