<div class="province form">
<?php echo $this->Form->create('Provincia');?>
	<fieldset>
		<legend><?php echo __('Add Provincia'); ?></legend>
	<?php
		echo $this->Form->input('provincia');
		echo $this->Form->input('riferimenti');
		echo $this->Form->input('aperta');
		//echo $this->Form->input('Offerta');
		//echo $this->Form->input('Richiesta');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit'));?>
</div>
