<section>
	<div class="text-end">
		<a href="/admin/suppliers/" class="btn btn-outline-warning btn-sm">Cancel</a>
	</div>
	<article>

		<h2>Select Supplier</h2>
		<?php
		echo $this->Form->select('Supplier.name', $suppliers, [
					'empty' => '- Select Supplier -',
					'onchange' => 'window.location = "/admin/suppliers/products/"+this.value',
					'default' => $supplierId,
					'class' => 'form-select form-select-sm'
				]);
		?>

		<?php
		if($supplierId) {
		?>
		<hr class="mt-4">
		<header><h2><?= $suppliers[$supplierId] ?> - Products</h2></header>
		<?= $this->Form->create() ?>
		<table>
			<thead>
			<tr>
				<th>Sl.No.</th>
				<th>Product Name</th>
				<th>Price Relation</th>
				<th>Relative Price</th>
			</tr>

			</thead>
		</table>

		<div class="mt-4">
			<button type="submit" class="btn btn-primary">Submit</button>
		</div>

		<?= $this->Form->end() ?>
		<?php
		}
		?>
	</article>
</section>
<br><br>
