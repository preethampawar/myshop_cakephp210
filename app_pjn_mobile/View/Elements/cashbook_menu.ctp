<div class="menu-item">
	<h4>Cashbook</h4>
	<ul>
		<li><?php echo $this->Html->link('Add New Record', array('controller'=>'cashbook', 'action'=>'add'));?></li>
		<li><?php echo $this->Html->link('Show All Records', array('controller'=>'cashbook', 'action'=>'index'));?></li>
	</ul>

    <h4>Cashbook Categories</h4>
    <ul>
        <li><?php echo $this->Html->link('Add New Category', array('controller'=>'categories', 'action'=>'add'));?></li>
        <li><?php echo $this->Html->link('Show All Categories', array('controller'=>'categories', 'action'=>'index'));?></li>
    </ul>
</div>