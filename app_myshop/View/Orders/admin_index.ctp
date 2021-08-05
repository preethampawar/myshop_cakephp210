<h1>Manage Orders</h1>

<div class="mt-3">
	<div class="btn-group">
		<button type="button" class="btn btn-sm btn-purple dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
			Filter By Status - <?= $orderType ?>
		</button>
		<ul class="dropdown-menu">
			<li><a class="dropdown-item" href="/admin/orders/index/<?= Order::ORDER_STATUS_NEW ?>"><?= Order::ORDER_STATUS_NEW ?></a></li>
			<li><a class="dropdown-item" href="/admin/orders/index/<?= Order::ORDER_STATUS_CONFIRMED ?>"><?= Order::ORDER_STATUS_CONFIRMED ?></a></li>
			<li><a class="dropdown-item" href="/admin/orders/index/<?= Order::ORDER_STATUS_SHIPPED ?>"><?= Order::ORDER_STATUS_SHIPPED ?></a></li>
			<li><a class="dropdown-item" href="/admin/orders/index/<?= Order::ORDER_STATUS_DELIVERED ?>"><?= Order::ORDER_STATUS_DELIVERED ?></a></li>
			<li><a class="dropdown-item" href="/admin/orders/index/<?= Order::ORDER_STATUS_CLOSED ?>"><?= Order::ORDER_STATUS_CLOSED ?></a></li>
			<li><a class="dropdown-item" href="/admin/orders/index/<?= Order::ORDER_STATUS_DRAFT ?>"><?= Order::ORDER_STATUS_DRAFT ?></a></li>
			<li><a class="dropdown-item" href="/admin/orders/index/<?= Order::ORDER_STATUS_CANCELLED ?>"><?= Order::ORDER_STATUS_CANCELLED ?></a></li>
		</ul>
	</div>
</div>

<?php
if ($orderType) {
	?>
		<div class="mt-3">
		Showing all "<b><?= $this->Paginator->params()['count'] ?></b>" <span class="text-orange fw-bold"><?= $orderType ?></span> orders.
		</div>
	<?php
}
?>

<div class="mt-3">
	<?php
	if (!empty($orders)) {
		$totalOrderValue = 0;
	?>
		<div class="table-responsive">
			<table class="table text-center">
				<thead>
				<tr>
					<th>Order No.</th>
					<th>Status</th>
					<th>Order Value</th>
					<th>Customer</th>
					<th>Mobile</th>
					<th>Created On</th>
				</tr>
				</thead>
				<tbody>
					<?php
					$i = 0;
					foreach ($orders as $row) {
						$i++;
						$orderId = $row['Order']['id'];
						$status = $row['Order']['status'];
						$mobile = $row['Order']['customer_phone'] ?: null ;
						$customerName = $row['Order']['customer_name'] ?: null ;
						$totalAmount = $row['Order']['total_order_amount'];
						$totalOrderValue += (float)$totalAmount;
						$modifiedDate = date('d-m-Y', strtotime($row['Order']['modified']));
						$createdDate = null;
						$log = !empty($row['Order']['log']) ? json_decode($row['Order']['log'], true) : null;

						if ($log) {
							foreach($log as $row2) {
								if ($row2['orderStatus'] == Order::ORDER_STATUS_NEW) {
									$createdDate = date('d-m-Y', $row2['date']);
									break;
								}
							}
						}
						$createdDate = $createdDate ?: $modifiedDate;
						?>
						<tr>
							<td><a href="/admin/orders/details/<?= base64_encode($orderId)?>"><?= $orderId ?></a></td>
							<td><?= $status ?></td>
							<td><?= $this->App->price($totalAmount) ?></td>
							<td><?= $customerName ?></td>
							<td><?= $mobile ?></td>
							<td><?= $createdDate ?></td>
						</tr>
						<?php
					}
					?>
				</tbody>
				<tfoot>
				<tr>
					<th colspan="2" class="text-start">Total Order Value</th>
					<th><?= $this->App->price($totalOrderValue) ?></th>
					<th></th>
					<th></th>
					<th></th>
				</tr>
				</tfoot>
			</table>
		</div>

		<br>
		<?php
		// prints X of Y, where X is current page and Y is number of pages
		echo 'Page ' . $this->Paginator->counter();
		echo '&nbsp;&nbsp;&nbsp;&nbsp;';

		// Shows the next and previous links
		echo '&laquo;' . $this->Paginator->prev('Prev', null, null, ['class' => 'disabled']);
		echo '&nbsp;&nbsp;';
		// Shows the page numbers
		echo $this->Paginator->numbers();

		echo '&nbsp;&nbsp;';
		echo $this->Paginator->next('Next', null, null, ['class' => 'disabled']) . '&raquo;';
	} else {
		?>
		No orders found.
		<?php
	}
	?>
</div>

