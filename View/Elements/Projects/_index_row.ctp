        <tr>
		<td><?php echo h($project['Project']['id']); ?>&nbsp;</td>
                <td style="width:240px;">
                    <h3<?php 
                        //margin for the "tree" elements, indent subprojects
                        if($this->request->action == 'projects_list' && $project['Project']['deep'])  {
                            $indet= (int)$project['Project']['deep']*25;
                            echo ' style="margin-left:'.$indet.'px;"'; 
                        }
                        ?>>
                        
                        <?php echo $this->Html->link($project['Project']['name'], array('action' => 'view', $project['Project']['id'])); ?>
                    </h3>
                    <p>
                        <?php 
                            if($this->request->action == 'index') 
                                echo nl2br(h($this->Text->truncate($project['Project']['description']))); 
                        ?>
                    </p>
                </td>		
		<td>
			<?php echo $this->Html->link($project['Type']['name'], array('controller' => 'projects', 'action' => 'index', 'type' => $project['Type']['id'])); ?>
		</td>
		<td>
                    Start: <?php echo h($this->Time->format('m/d/Y',$project['Project']['start_date'])); ?><br />
                    Due:<?php echo h($this->Time->format('m/d/Y',$project['Project']['due_date'])); ?>
                </td>
		<td>
			<?php echo $this->Html->link($project['Status']['name'], array('controller' => 'projects', 'action' => 'index', 'status' => $project['Status']['id'])); ?>
		</td>
                <td>
			<?php echo $this->Html->link($project['User']['username'], array('controller' => 'projects', 'action' => 'index', 'user' => $project['User']['id'])); ?>
		</td>
                <?php if($this->request->action != 'view'): ?>
		<td>
			<?php echo $this->Html->link($project['ParentProject']['name'], array('controller' => 'projects', 'action' => 'view',  $project['ParentProject']['id'])); ?>
		</td>
		<?php endif; ?>
                <td style="width:180px;">
                    Created: <?php echo h($this->Time->format('m/d/Y H:i',$project['Project']['created'])); ?> <br />
                    Last edit:<?php echo h($this->Time->format('m/d/Y H:i',$project['Project']['modified'])); ?>&nbsp;
                </td>
		<td class="actions">
			
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $project['Project']['id'])); ?>
			<?php 
                            if($project['Project']['rght'] == $project['Project']['lft'] + 1 ) {
                                echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $project['Project']['id']), null, __('Are you sure you want to delete # %s?', $project['Project']['id'])); 
                            }
                        ?>
                        <br />
                        <?php echo $this->Html->link(__('Add subproject'), array('action' => 'add', $project['Project']['id'])); ?> [<?php echo ($project['Project']['rght'] - $project['Project']['lft']-1)/2;?>]
		</td>
	</tr>