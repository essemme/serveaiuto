<div style="width: 70px;">
<?php
if(is_null($value) || !$value) $value = '0';

if(!isset($label))
$label = $field;

echo $this->Html->image(''. (string)$value .'.png', array(
    'url' => array('action' => 'attiva_disattiva', $record_id, $field),
    'title' => $label
    )
);

if($label == 'in_evidenza') $label = 'evidenza';
echo $label; 
 
?>
</div>