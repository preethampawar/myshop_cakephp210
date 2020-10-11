<div class="menu-item">
	<h4>Invoice</h4>
	<ul>
		<li><?php echo $this->Html->link('Add New Invoice', array('controller'=>'invoices', 'action'=>'add'));?></li>
		<li><?php echo $this->Html->link('Show All Invoices', array('controller'=>'invoices', 'action'=>'index'));?></li>
		<li><?php echo $this->Html->link('Show Purchase Invoices', array('controller'=>'invoices', 'action'=>'index', 'purchase'));?></li>
		<li><?php echo $this->Html->link('Show Sale Invoices', array('controller'=>'invoices', 'action'=>'index', 'sale'));?></li>
	</ul>
	
	<h4>Suppliers</h4>
	<ul>
		<li><?php echo $this->Html->link('Add New Supplier', array('controller'=>'suppliers', 'action'=>'add'));?></li>
		<li><?php echo $this->Html->link('Suppliers List', array('controller'=>'suppliers', 'action'=>'index'));?></li>
	</ul>	
</div>