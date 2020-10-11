<div>
	<?php 
	echo $this->Form->create();
	echo $this->Form->input('Store.name', array('label'=>'Store Name', 'required'=>true, 'type'=>'text', 'title'=>'Enter Store Name'));
	echo $this->Form->input('Store.user_id', array('label'=>'User', 'required'=>true, 'type'=>'select', 'options'=>$userInfo));
	echo $this->Form->input('Store.created', array('label'=>'Created Date', 'required'=>true, 'type'=>'date'));
	echo $this->Form->submit('Update Store');
	echo $this->Form->end();
	echo '<br>';
	echo $this->Html->link('Cancel', array('controller'=>'stores', 'action'=>'index'));
	?>
</div>