<div class="menu-item">
	<h4>Invoices & Quotations</h4>
	<ul>
		<li><?php echo $this->Html->link('+ Add New Invoice / Quotation', array('controller'=>'invoice_quotations', 'action'=>'selectTemplate'));?></li>
        <li><?php echo $this->Html->link('Show All Invoices', array('controller'=>'invoice_quotations', 'action'=>'index', 'invoice'));?></li>
        <li><?php echo $this->Html->link('Show All Quotations', array('controller'=>'invoice_quotations', 'action'=>'index', 'quotation'));?></li>
    </ul>

    <h4>Templates</h4>
    <ul>
        <li><?php echo $this->Html->link('+ Create Template', array('controller'=>'invoice_quotations', 'action'=>'createTemplate'));?></li>
        <li><?php echo $this->Html->link('Show All Templates', array('controller'=>'invoice_quotations', 'action'=>'index', 'template'));?></li>
    </ul>
	<!-- 
	<h4>Bank Report</h4>
	<ul>		
		<li><?php echo $this->Html->link('Bank Report', array('controller'=>'reports', 'action'=>'bankReport'));?></li>
	</ul>
	-->	
</div>