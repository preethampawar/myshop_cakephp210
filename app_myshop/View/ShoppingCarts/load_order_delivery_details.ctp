<?php
App::uses('Order', 'Model');

if (isset($shoppingCartProducts['ShoppingCartProduct']) and !empty($shoppingCartProducts['ShoppingCartProduct'])) {
	$cartValue = 0;
	$totalItems = 0;

	foreach ($shoppingCartProducts['ShoppingCartProduct'] as $row) {
		$qty = $row['quantity'] ?: 0;
		$mrp = $row['mrp'];
		$discount = $row['discount'];
		$salePrice = $mrp - $discount;
		$totalProductPurchaseValue = $salePrice * $qty;
		$cartValue += $totalProductPurchaseValue;
		$totalItems += $qty;
	}

	$payableAmount = $cartValue + $this->Session->read('Site.shipping_charges');
	?>

	<?php echo $this->Form->create('ShoppingCart', ['url' => '/ShoppingCarts/placeOrder', 'method' => 'post', 'class' => 'needs-validation', 'novalidate']) ?>

	<div id="deliveryErrorAlert" class="alert alert-danger alert-dismissible fade show d-none" role="alert">
		<div class="content"></div>
		<button type="button" class="btn-close" aria-label="Close" onclick="$('#deliveryErrorAlert').addClass('d-none')"></button>
	</div>

	<div class="">

		<div class="">
			<label for="orderCustomerName">Contact Name <span class="text-danger small">(required)</span></label>
			<input
				type="text"
				name="data[customer_name]"
				id="orderCustomerName"
				minlength="2"
				maxlength="55"
				class="form-control form-control-sm"
				value="<?= $orderDetails['Order']['customer_name'] ?>"
				required>
		</div>

		<div class="mt-3">
			<label for="orderCustomerPhone">Contact Phone No. <span class="text-danger small">(required)</span></label>
			<input
				type="tel"
				name="data[customer_phone]"
				id="orderCustomerPhone"
				class="form-control form-control-sm"
				value="<?= $orderDetails['Order']['customer_phone'] ?>"
				required>
		</div>

		<div class="mt-3">
			<label for="orderCustomerAddress">Delivery Address <span class="text-danger small">(required)</span></label>
			<textarea
				name="data[customer_address]"
				id="orderCustomerAddress"
				rows="3"
				class="form-control form-control-sm"
				required><?= $orderDetails['Order']['customer_address'] ?></textarea>
		</div>

		<div class="mt-3">
			<label for="orderCustomerMessage">Special Instructions <span class="text-muted small">(optional)</span></label>
			<textarea
				name="data[customer_message]"
				id="orderCustomerMessage"
				rows="3"
				class="form-control form-control-sm"><?= $orderDetails['Order']['customer_message'] ?></textarea>
		</div>
	</div>
	<div class="mt-5 text-center">
		<div class="d-flex justify-content-center">
			<button type="button" class="btn btn-secondary me-4" onclick="orderDeliveryDetails.hide(); myShoppingCart.show()">Back</button>
			<button type="button" id="saveOrderDeliveryDetailsButton" class="btn btn-orange" onclick="saveOrderDeliveryDetails()">Next &raquo;</button>
		</div>
	</div>

	<?php echo $this->Form->end() ?>

	<?php
} else {
	?>
	<div class="bg-white">
		No items in your cart.
	</div>
	<?php
}
?>
<div id="orderDeliveryDetailsSpinner" class="mt-4"></div>

<br><br><br><br>

