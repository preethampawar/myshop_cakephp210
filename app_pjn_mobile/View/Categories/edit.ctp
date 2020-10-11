<p><?php echo $this->Html->link('Cancel', array('controller'=>'cashbook', 'action'=>'index'));?></p>

<?php $this->start('cashbook_menu');?>
<?php echo $this->element('cashbook_menu');?>
<?php $this->end();?>



<h1>Cashbook - Edit Category - <?php echo $pCatInfo['Category']['name'];?></h1> <br>

<div id="AddCategoryDiv" class="well">
    <?php echo $this->Form->create();?>

    <?php echo $this->Form->input('name', array('placeholder'=>'Enter Category Name', 'label'=>'Category Name', 'required'=>true)); ?>
    <?php echo $this->Form->input('expense', array('type'=>'checkbox', 'label'=>'Expense')); ?>
    <?php echo $this->Form->input('income', array('type'=>'checkbox', 'label'=>'Income')); ?>

    <button class="btn btn-primary btn-sm" type="submit">Update Category</button>
    <br><br><br>
    <?php echo $this->Html->link("Cancel", array('controller'=>'categories', 'action'=>'index'), array( 'escape'=>false, 'class'=>'btn btn-warning btn-sm'));?>
    <?php echo $this->Form->end();?>
</div>