<?php
App::uses('ShoppingCart', 'Model');
$shoppingCartModel = new ShoppingCart;
$shoppingCart = $shoppingCartModel->getShoppingCartProducts($this->Session->read('ShoppingCart.id'));

$selectBoxQuantityOptions = '';
for ($i = 1; $i <= 50; $i++) {
	$selectBoxQuantityOptions .= "<option value='$i'>$i</option>";
}

$totalItems = 0;
if (isset($shoppingCart['ShoppingCartProduct']) and !empty($shoppingCart['ShoppingCartProduct'])) {
	foreach ($shoppingCart['ShoppingCartProduct'] as $row) {
		$totalItems += $row['quantity'];
	}
	?>

	<div class="bg-white">

		<?php
		$i = 0;
		$cartValue = 0;
		foreach ($shoppingCart['ShoppingCartProduct'] as $row) {
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
			if ($imageDetails) {
				$thumbUrl = $assetDomainUrl . $imageDetails['thumb']->imagePath;
			}
			?>

			<div class="mt-2 mb-4">
				<div id="topNavCartRow<?php echo $categoryID . '-' . $productID; ?>">
					<div class="p-1 pb-3 border rounded">
						<div class="bg-light rounded p-2">
							<div class="d-flex justify-content-between">
											<span onclick="bsMyShoppingCart.hide(); showProductDetails('<?php echo $categoryID; ?>', '<?php echo $productID; ?>')"
												  role="button" class="text-primary">
												<?= $productName ?>
											</span>

								<div>
									<?php
									echo $this->Html->link(
											'<i class="fa fa-times"></i>',
											'/ShoppingCarts/deleteShoppingCartProduct/' . $shoppingCartProductID,
											[
													'title' => 'Remove from cart: ' . $categoryNameSlug . ' &raquo; ' . $productNameSlug,
													'escape' => false,
													'class' => 'text-danger p-2'
											],
											'Are you sure you want to delete this product. ' . $categoryName . ' &raquo; ' . $productName . ', quantity: ' . $qty
									);
									?>
								</div>
							</div>

						</div>
						<div class="d-flex mt-2">
							<img
									src="<?php echo $thumbUrl; ?>"
									loading="lazy"
									class=""
									role="button"
									alt="<?php echo $productName; ?>"
									id="<?php echo $imageTagId; ?>"
									style="width: 75px; height: 75px"
									onclick="bsMyShoppingCart.hide(); showProductDetails('<?php echo $categoryID; ?>', '<?php echo $productID; ?>');"
							/>
							<div class="ms-2">
								<div class="small text-muted">
									Quantity: <?= $qty ?><br>
									Price: <?php echo $this->App->price($salePrice); ?>/unit,
									<span class="small text-decoration-line-through">MRP <?php echo $this->App->price($mrp); ?></span>
								</div>
								<div class="d-flex mt-2">
									<div>
										<span class="text-danger fw-bold fs-5"><?php echo $this->App->price($productCartValue); ?></span>
									</div>
									<?php if ($showDiscount): ?>
										<div class="ms-2 mt-1">
											<span class="small text-decoration-line-through">MRP <?php echo $this->App->price($productCartMRPValue); ?></span>
										</div>
									<?php endif; ?>
								</div>
								<?php if ($showDiscount): ?>
									<div class="text-success fw-bold small">
										Save <?php echo $this->App->priceOfferInfo($productCartValue, $productCartMRPValue); ?>
									</div>
								<?php endif; ?>
							</div>
						</div>
						<div class="mt-2">
							<div class="small">Quantity:</div>
							<div class="d-flex">

								<input
										type="number"
										id="ProductQuantity<?= $shoppingCartProductID ?>"
										name="data[Product][quantity]"
										class="form-control form-control-sm w-50"
										min="1"
										max="100"
										data-shopping-cart-product-id="<?= $shoppingCartProductID ?>"
										data-actual-qty="<?= $qty ?>"
										value="<?= $qty ?>"
										required
								>
								<div>
									<button
											class="btn btn-sm btn-primary ms-2"
											onclick="updateProductQtyFromShoppingCart('<?php echo $categoryID; ?>', '<?php echo $productID; ?>', $('#ProductQuantity<?= $shoppingCartProductID ?>').val(), '<?= $shoppingCartProductID ?>')">
										Update
									</button>
								</div>
								<div id="updatingCartSpinner<?= $shoppingCartProductID ?>"
									 class="spinner-border spinner-border-sm text-primary ms-3 mt-2 small d-none" role="status">
									<span class="visually-hidden">updating...</span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php
		}
		?>

		<div class="mt-5 text-center">
			<?php
			echo $this->Form->create(null, ['url' => '/RequestPriceQuote', 'method' => 'get', 'encoding' => false]);
			?>
			<button class="btn btn-orange">Book Order</button>
			<?php
			//echo $this->Form->submit('Book Order', ['escape' => false, 'div' => false]);
			echo $this->Form->end();
			?>
			<br>

			<a href="#" type="button" class="small" data-bs-dismiss="offcanvas" aria-label="Close">Hide Cart</a>
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

