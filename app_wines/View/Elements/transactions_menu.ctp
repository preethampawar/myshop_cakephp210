<div class="menu-item">
	<h4>Transactions</h4>
	<ul>
		<li><?php echo $this->Html->link('Add New Transaction', array('controller'=>'transactions', 'action'=>'add'));?></li>
		<li><?php echo $this->Html->link('Show All Transactions', array('controller'=>'transactions', 'action'=>'index'));?></li>

	</ul>

	<h4>Transactions Category</h4>
	<ul>
		<li><?php echo $this->Html->link('Manage Transaction Categories', array('controller'=>'TransactionCategories', 'action'=>'add'));?></li>

	</ul>

	<h4>Transactions Report</h4>
	<ul>
		<li><?php echo $this->Html->link('Transactions Report', array('controller'=>'reports', 'action'=>'transactionsReport'));?></li>
	</ul>
</div>
