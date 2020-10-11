<div>
	<?php 
	echo $this->Form->create();
	echo $this->Form->input('Store.name', array('label'=>'Store Name', 'required'=>true, 'type'=>'text', 'title'=>'Enter Store Name'));
	echo $this->Form->input('Store.user_id', array('label'=>'User', 'required'=>true, 'type'=>'select', 'options'=>$userInfo));
	echo $this->Form->submit('Create Store');
	echo $this->Form->end();
	?>
</div>