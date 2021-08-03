<p>Thank you for your order. You will be notified once the order is confirmed.</p>
<br>
<p>Below are your order details:</p>

<p>
	<h2>Order No. #<?= $order['Order']['id']; ?></h2>
</p>


<?php
if (isset($order['OrderProduct']) and !empty($order['OrderProduct'])) {
	?>

	<div class="p-3 shadow small mt-4">
		<h5>PRODUCTS</h5>

		<table class="table small" style="width:100%" border="1">
			<thead>
			<tr>
				<th style="text-align: left;">Product</th>
				<th style="text-align: left;" class="text-center">Price</th>
				<th style="text-align: left;" class="text-center">Qty</th>
				<th style="text-align: left;" class="text-center">Amount</th>
			</tr>
			</thead>
			<tbody>
			<?php
			$i = 0;
			$cartValue = $order['Order']['total_cart_value'];
			$payableAmount = $order['Order']['total_order_amount'];
			$totalDiscount = $order['Order']['total_discount'];

			$cartMrpValue = 0;
			$totalItems = 0;
			foreach ($order['OrderProduct'] as $row) {
				$i++;
				$categoryName = ucwords($row['category_name']);
				$productName = ucwords($row['product_name']);
				$qty = $row['quantity'] ?: 0;
				$mrp = $row['mrp'];
				$discount = $row['discount'];
				$salePrice = $row['sale_price'];
				$showDiscount = $mrp != $salePrice;
				$totalProductPurchaseValue = $salePrice * $qty;

				$productCartValue = $qty * $salePrice;
				$productCartMRPValue = $qty * $mrp;
				$totalItems += $qty;
				$cartMrpValue += $productCartMRPValue;
				//$totalDiscount += $qty * $discount;
				?>

				<tr>
					<td><?= $productName ?></td>
					<td class="text-center">
						<?= $this->App->price($salePrice) ?>
					</td>
					<td class="text-center">
						<?=  $qty ?>
					</td>
					<td class="text-center">
						<?= $this->App->price($productCartValue) ?>
					</td>
				</tr>

				<?php
			}
			?>

			</tbody>
			<tfoot>
			<tr class="text-muted">
				<td>Total Cart Value</td>
				<td class="text-decoration-line-through text-center"></td>
				<td class="text-center"></td>
				<td class="text-center"><?= $this->App->price($cartValue) ?></td>
			</tr>
			<tr class="text-muted">
				<td>Shipping Charges</td>
				<td></td>
				<td class="text-center"></td>
				<td class="text-center"><?= $this->App->price($order['Order']['shipping_amount']) ?></td>
			</tr>
			<tr class="fw-bold">
				<td>Total</td>
				<td></td>
				<td class="text-center"><?= $totalItems ?></td>
				<td class="text-center"><?= $this->App->price($payableAmount) ?></td>
			</tr>

			</tfoot>

		</table>
		<br>
		<div class="text-success text-center">You have saved <?= $this->App->price($totalDiscount) ?> on this Order</div>
	</div>
	<br><br>
	<div class="p-3 mt-4 shadow small">
		<h5>DELIVERY DETAILS</h5>
		<hr>
		<div class="">
			Contact Name:<br>
			<b><?= $order['Order']['customer_name'] ?></b>
		</div>
		<div class="mt-2">
			Contact Phone No:<br>
			<b><?= $order['Order']['customer_phone'] ?></b>
		</div>
		<div class="mt-2">
			Delivery Address:<br>
			<b><?= $order['Order']['customer_address'] ?></b>
		</div>
		<div class="mt-2">
			Special Instructions:<br>
			<b><?= h($order['Order']['customer_message']) ?></b>
		</div>
	</div>
	<br><br>
	<div class="p-3 mt-4 shadow small">
		<h5>PAYMENT DETAILS</h5>
		<hr>
		<div class="">
			Payment Method:
			<b><?= $order['Order']['payment_method'] ?></b>
		</div>
		<div class="mt-2">
			Payment Reference No:
			<b><?= !empty($order['Order']['payment_reference_no']) ? $order['Order']['payment_reference_no'] : '-' ?></b>
		</div>
	</div>
	<?php
} else {
	?>
	<div class="bg-white">
		No items in your order.
	</div>
	<?php
}
exit;
?>
<br>
<p>This is an auto generated email. Please do not respond.</p>
<p>-<br>
<?= $this->Html->url('/', true) ?>
</p>
