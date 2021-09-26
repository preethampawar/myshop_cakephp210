<h1>Manage Orders</h1>



<div class="mt-4 d-none d-lg-block">
	<?php
	$orderOptions = Order::ORDER_STATUS_OPTIONS;
	//$this->set('ordersCountByStatus', $ordersCountByStatus);
	//$this->set('archivedOrdersCount', $archivedOrdersCount);

	$ordersCountStatus = [];
	foreach($ordersCountByStatus as $row) {
		$ordersCountStatus[$row['orders']['status']] = $row[0]['count'];
	}

	$archivedOrdersCount =  (isset($archivedOrdersCount[0][0]['count']) && !empty($archivedOrdersCount[0][0]['count'])) ? $archivedOrdersCount[0][0]['count'] : 0;

	foreach($orderOptions as $option) {
		$btnColor = 'btn-outline-secondary';
		$bgColor = 'bg-secondary';

		switch ($option) {
			case Order::ORDER_STATUS_DRAFT:
				$btnColor = 'btn-outline-secondary';
				$bgColor = 'bg-secondary';
				break;
			case Order::ORDER_STATUS_NEW:
				$btnColor = 'btn-primary';
				$bgColor = 'bg-danger';
				break;
			case Order::ORDER_STATUS_CONFIRMED:
				$btnColor = 'btn-outline-info';
				$bgColor = 'bg-info';
				break;
			case Order::ORDER_STATUS_SHIPPED:
			case Order::ORDER_STATUS_DELIVERED:
				$btnColor = 'btn-outline-warning';
				$bgColor = 'bg-warning';
				break;
			// case Order::ORDER_STATUS_RETURNED:
			case Order::ORDER_STATUS_CANCELLED:
				$btnColor = 'btn-outline-danger';
				$bgColor = 'bg-danger';
				break;
			case Order::ORDER_STATUS_CLOSED:
				$btnColor = 'btn-outline-success';
				$bgColor = 'bg-success';
				break;
			default:
				break;
		}

//		if ($option == Order::ORDER_STATUS_DRAFT) {
//			continue;
//		}
		?>
		<a href="/admin/orders/index/<?= $option ?>" role="button" class="btn <?= $btnColor ?> btn-sm position-relative me-3 mb-3">
			<span class="small"><?= $option ?></span>
			<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill <?= $bgColor ?> small">
				<?= $ordersCountStatus[$option] ?? 0 ?>
			</span>
		</a>
		<?php
	}
	?>
	<!--
	<a href="/admin/orders/index/<?= Order::ORDER_STATUS_DRAFT ?>" role="button" class="btn btn-outline-secondary btn-sm position-relative me-2 mb-3">
		<?= Order::ORDER_STATUS_DRAFT ?>
		<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-secondary">
			<?= $ordersCountStatus[Order::ORDER_STATUS_DRAFT] ?>
		</span>
	</a>
	<a href="/admin/orders/index/ARCHIVED" role="button" class="btn btn-secondary btn-sm position-relative me-2 mb-3">
		ARCHIVED
		<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
			<?= $ordersCountStatus[$option] ?? 0 ?>
		</span>
	</a>
	-->
</div>

<div class="mt-4 d-block d-lg-none">
	<div class="list-group">

		<?php
		foreach($orderOptions as $option) {
			$btnColor = 'btn-outline-secondary';
			$bgColor = 'bg-secondary';

			switch ($option) {
				case Order::ORDER_STATUS_DRAFT:
					$btnColor = 'btn-outline-secondary';
					$bgColor = 'bg-secondary';
					break;
				case Order::ORDER_STATUS_NEW:
					$btnColor = 'btn-primary';
					$bgColor = 'bg-danger';
					break;
				case Order::ORDER_STATUS_CONFIRMED:
					$btnColor = 'btn-outline-info';
					$bgColor = 'bg-info';
					break;
				case Order::ORDER_STATUS_SHIPPED:
				case Order::ORDER_STATUS_DELIVERED:
					$btnColor = 'btn-outline-warning';
					$bgColor = 'bg-warning';
					break;
				// case Order::ORDER_STATUS_RETURNED:
				case Order::ORDER_STATUS_CANCELLED:
					$btnColor = 'btn-outline-danger';
					$bgColor = 'bg-danger';
					break;
				case Order::ORDER_STATUS_CLOSED:
					$btnColor = 'btn-outline-success';
					$bgColor = 'bg-success';
					break;
				default:
					break;
			}
//			if ($option == Order::ORDER_STATUS_DRAFT) {
//				continue;
//			}
			?>
			<a href="/admin/orders/index/<?= $option ?>" class="list-group-item list-group-item-action ">
				<span class="text-primary"><?= $option ?></span>
				<span class="badge <?= $bgColor ?> rounded-pill ms-1"><?= $ordersCountStatus[$option] ?? 0 ?></span>
			</a>
			<?php
		}
		?>
	</div>

</div>

<div class="mt-3 d-flex justify-content-end d-none">
	<div class="btn-group">
		<button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
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


<div class="mt-4 text-end">
	<a href="/admin/orders/createOrder" class="btn btn-sm btn-primary">+ Create Offline Order</a>
	<a href="/admin/orders/archived" class="btn btn-sm btn-secondary disabled ms-2 d-none">Archived Orders</a>
</div>

<?php
if ($orderType) {
	?>
		<div class="mt-3">
			<b><?= $this->Paginator->params()['count'] ?></b> '<span class="text-orange fw-bold"><?= $orderType ?></span>' orders.
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
			<table class="table table-sm small" style="min-height:200px;">
				<thead>
				<tr>
					<th>Order No.</th>
					<th>Status</th>
					<th>Order Value</th>
					<th>Customer</th>
					<th>Mobile</th>
					<th>Created On</th>
					<th></th>
				</tr>
				</thead>
				<tbody>
					<?php
					$i = 0;
					foreach ($orders as $row) {
						$i++;
						$orderId = $row['Order']['id'];
						$status = $row['Order']['status'];
						$offlineOrder = $row['Order']['is_offline_order'] ?? 0;
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
						$encodedOrderId = base64_encode($orderId);
						$encodedArchiveText = base64_encode(Order::ORDER_ARCHIVE);
						$archiveUrl = '/admin/orders/archive/' . $encodedOrderId . '/' . $encodedArchiveText;
						$archiveContent = 'Are you sure you want to archive this order #' . $orderId . '?';
						$showEditLink = ($status === Order::ORDER_STATUS_DRAFT && $offlineOrder)  || $status === Order::ORDER_STATUS_NEW;
						?>
						<tr>
							<td>
								<a class="" href="/admin/orders/details/<?= base64_encode($orderId)?>"><?= $orderId ?></a>
							</td>
							<td class="text-start">
								<?= $status ?>
								<span class="ms-2" title="<?= $offlineOrder ? 'Offline Order' : 'Online Order' ?>">
									<?= $offlineOrder ? '<i class="fa fa-headset text-warning"></i>' : '<i class="fa fa-mobile-alt text-success"></i>' ?>
								</span>
							</td>
							<td><?= $this->App->price($totalAmount) ?></td>
							<td><?= $customerName ?></td>
							<td><?= $mobile ?></td>
							<td><?= $createdDate ?></td>
							<td class="text-end text-nowrap">

								<?php if ($showEditLink) { ?>
									<a class="btn btn-primary btn-sm me-2" href="/admin/orders/saveOrder/<?= base64_encode($orderId)?>">Edit</a>
								<?php } ?>

								<a class="btn btn-outline-danger btn-sm" href="#" onclick="showConfirmPopup('<?= $archiveUrl ?>', 'Archive Order', '<?= $archiveContent ?>'); return false;">Archive</a>
							</td>
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
<br><br><br>

