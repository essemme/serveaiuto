<div class="users form">
<?php echo $this->Form->create('User');?>
	<fieldset>
		<legend><?php echo __('Edit User') . ' ' . $this->data['username'];  ?></legend>
	<?php
		echo $this->Form->input('id');
		//echo $this->Form->input('username');
		echo $this->Form->input('password');
		echo $this->Form->input('email');		                
		echo $this->Form->input('provincia_id');
                //echo $this->Form->input('facebook_id', array('type' => 'text'));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit'));?>
</div>

