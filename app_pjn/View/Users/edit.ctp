<div class="users form">
<?php echo $this->Form->create('User'); ?>
    <fieldset>
        <legend><?php echo __('Update your account'); ?></legend>
        <?php 
			echo $this->Form->input('name', array('label'=>'Full Name', 'placeholder'=>'Enter your full name'));
			echo $this->Form->input('email', array('label'=>'Email Address', 'placeholder'=>'Enter your email address', 'type'=>'text'));
			echo $this->Form->input('password', array('label'=>'Password', 'placeholder'=>'Enter your password'));
        ?>
    </fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>