                        <span  class="btn btn-small tool-tip" rel="tooltip" title="<?php 
                            $tags = array();
                            
                            foreach ( $tags_array as $tag ) {
                                $tags[] = $tag['nome'];
                            }
                            echo join(', ', $tags);
                            ?>">
                            <?php 
                            echo $this->Html->image('tag_parole_chiave.png',array('style' => 'float:left; padding-right:4px;', 'escape' => 'false')); 
                            echo count($tags_array);
                            ?>
                            Parole chiave
                        </span>