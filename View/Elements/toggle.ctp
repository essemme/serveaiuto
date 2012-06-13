<div style="width: 70px;">
<?php
if(is_null($value) || !$value) $value = '0';

echo $this->Html->image(''. (string)$value .'.png', array('url' => array('action' => 'attiva_disattiva', $record_id, $field)));

if($field == 'in_evidenza') $field = 'evidenza';
echo $field; 
 
?>
</div>