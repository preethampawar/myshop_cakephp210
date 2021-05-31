
<?php
if (isset($shoppingCartProducts['ShoppingCartProduct']) and !empty($shoppingCartProducts['ShoppingCartProduct'])) {
	?>

	<div class="p-3 shadow small">
		<h5>PRODUCTS</h5>
		<hr>
		<table class="table small">
			<thead>
			<tr>
				<th>Product</th>
				<th>Price</th>
				<th>Qty</th>
				<th>Amount</th>
			</tr>
			</thead>
			<tbody>
		<?php
		$i = 0;
		$cartValue = 0;
		$cartMrpValue = 0;
		$totalItems = 0;
		$totalDiscount = 0;
		foreach ($shoppingCartProducts['ShoppingCartProduct'] as $row) {
			$i++;
			$shoppingCartProductID = $row['id'];
			$categoryID = $row['category_id'];
			$categoryName = ucwords($row['category_name']);
			$categoryNameSlug = Inflector::slug($categoryName, '-');

			$productID = $row['product_id'];
			$productName = ucwords($row['product_name']);
			$productNameSlug = Inflector::slug($productName, '-');
			$qty = $row['quantity'] ?: 0;
			$mrp = $row['mrp'];
			$discount = $row['discount'];
			$salePrice = $mrp - $discount;
			$showDiscount = $mrp != $salePrice;
			$totalProductPurchaseValue = $salePrice * $qty;
			$cartValue += $totalProductPurchaseValue;

			$assetDomainUrl = Configure::read('AssetDomainUrl');
			$loadingImageUrl = $assetDomainUrl . 'assets/images/loading/loading.gif';
			$productUploadedImages = $row['Product']['images'] ? json_decode($row['Product']['images']) : [];
			$imageDetails = $this->App->getHighlightImage($productUploadedImages);
			$thumbUrl = '/img/noimage.jpg';
			$imageTagId = random_int(1, 10000);
			$productCartValue = $qty * $salePrice;
			$productCartMRPValue = $qty * $mrp;
			$totalItems += $qty;
			$cartMrpValue += $productCartMRPValue;
			$totalDiscount += $qty * $discount;

			if ($imageDetails) {
				$thumbUrl = $assetDomainUrl . $imageDetails['thumb']->imagePath;
			}
			?>

			<tr>
				<td><?= $productName ?></td>
				<td>
					<?= $this->App->price($salePrice) ?>
					<br>
					<span class="small text-decoration-line-through">MRP <?php echo $this->App->price($mrp); ?></span>
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

		$payableAmount = $cartValue + $this->Session->read('Site.shipping_charges');
		?>

			</tbody>
			<tfoot>
				<tr class="text-muted">
					<td>Total Cart Value</td>
					<td class="text-decoration-line-through">MRP <?= $this->App->price($cartMrpValue) ?></td>
					<td class="text-center"></td>
					<td class="text-center"><?= $this->App->price($cartValue) ?></td>
				</tr>
				<tr class="text-muted">
					<td>Shipping Charges</td>
					<td></td>
					<td class="text-center"></td>
					<td class="text-center"><?= $this->App->price($this->Session->read('Site.shipping_charges')) ?></td>
				</tr>
				<tr class="fw-bold">
					<td>Total</td>
					<td></td>
					<td class="text-center"><?= $totalItems ?></td>
					<td class="text-center"><?= $this->App->price($payableAmount) ?></td>
				</tr>

			</tfoot>

		</table>
		<div class="text-success text-center">You have saved <?= $this->App->price($totalDiscount) ?> on this Order</div>
	</div>

	<div class="p-3 mt-4 shadow small">
		<h5>DELIVERY DETAILS</h5>
		<hr>
		<div class="">
			Contact Name:<br>
			<b><?= $orderDetails['Order']['customer_name'] ?></b>
		</div>
		<div class="mt-2">
			Contact Phone No:<br>
			<b><?= $orderDetails['Order']['customer_phone'] ?></b>
		</div>
		<div class="mt-2">
			Delivery Address:<br>
			<b><?= $orderDetails['Order']['customer_address'] ?></b>
		</div>
		<div class="mt-2">
			Special Instructions:<br>
			<b><?= $orderDetails['Order']['customer_message'] ?></b>
		</div>
	</div>

	<div class="p-3 mt-4 shadow small">
		<h5>PAYMENT DETAILS</h5>
		<hr>
		<div class="">
			Payment Method:
			<b><?= $orderDetails['Order']['payment_method'] ?></b>
		</div>
		<div class="mt-2">
			Payment Reference No:
			<b><?= !empty($orderDetails['Order']['payment_reference_no']) ? $orderDetails['Order']['payment_reference_no'] : '-' ?></b>
		</div>
	</div>

	<div class="mt-5 text-center">
		<div class="d-flex justify-content-center">
			<button type="button" class="btn btn-secondary me-4" onclick="orderSummary.hide(); orderPaymentDetails.show()">&laquo; BACK</button>
			<button type="button" class="btn btn-orange" id="placeOrderButton" onclick="placeOrder()">CONFIRM & PLACE ORDER</button>
		</div>
	</div>

	<?php
} else {
	?>
	<div class="bg-white">
		No items in your cart.
	</div>
	<?php
}
?>
<div id="confirmOrderSpinner" class="mt-4"></div>
<br><br><br><br>
