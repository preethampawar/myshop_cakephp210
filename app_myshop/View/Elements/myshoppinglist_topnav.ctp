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

	<div class="mb-4 shadow py-1 px-2 border-4 border-top border-warning">
		<nav class="navbar">
			<div class="container-fluid py-1 text-small">
				<div class="px-0 py-0 text-dark border-0" role="button" data-bs-toggle="collapse"
						data-bs-target="#topNavCart" aria-controls="topNavCart" aria-expanded="false"
						aria-label="Toggle navigation">
					<span class="fa fa-cart-arrow-down"></span>
					<span class="fw-bold small">My Cart</span>
					<span class="fa fa-caret-down"></span>
				</div>
				<div><b><?php echo $totalItems; ?></b> item(s) in <a href="#topNavCart" data-bs-toggle="collapse"
																	 data-bs-target="#topNavCart"
																	 aria-controls="topNavCart" aria-expanded="false"
																	 aria-label="Toggle navigation">cart</a>.
				</div>
			</div>
		</nav>
		<div class="collapse" id="topNavCart">
			<div class="bg-white p-2">

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
						$loadingImageUrl = $assetDomainUrl.'assets/images/loading/loading.gif';
						$productUploadedImages = $row['Product']['images'] ? json_decode($row['Product']['images']) : [];
						$imageDetails = $this->App->getHighlightImage($productUploadedImages);
						$thumbUrl = '/img/noimage.jpg';
						$imageTagId = random_int(1, 10000);
						$productCartValue = $qty * $salePrice;
						$productCartMRPValue = $qty * $mrp;
						if($imageDetails) {
							$thumbUrl = $assetDomainUrl . $imageDetails['thumb']->imagePath;
						}
						?>

						<div class="mt-2 mb-4">
							<div id="vueTopNavCartRow<?php echo $categoryID .'-'. $productID; ?>" >
								<div class="p-2 pb-3 shadow border rounded">
									<div class="bg-light rounded p-2">
										<div class="d-flex justify-content-between">

											<?php echo $this->Html->link(
												$productName,
												'/products/details/' . $categoryID . '/' . $productID,
												[
													'title' => $categoryNameSlug . ' &raquo; ' . $productNameSlug,
													'escape' => false,
													'class'=>'text-decoration-none'
												]
											); ?>

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
												onclick="showProductDetails('<?php echo $categoryID; ?>', '<?php echo $productID; ?>');"
										/>
										<div class="ms-2">
											<div class="small text-muted">
												Quantity: <?= $qty ?><br>
												Price: <?php echo $this->App->price($salePrice);?>/unit,
												<span class="small text-decoration-line-through">MRP <?php echo $this->App->price($mrp);?></span>
											</div>
											<div class="d-flex mt-2">
												<div>
													<span class="text-danger fw-bold fs-5"><?php echo $this->App->price($productCartValue);?></span>
												</div>
												<?php if($showDiscount): ?>
													<div class="ms-2 mt-1">
														<span class="small text-decoration-line-through">MRP <?php echo $this->App->price($productCartMRPValue);?></span>
													</div>
												<?php endif; ?>
											</div>
											<?php if($showDiscount): ?>
												<div class="text-success fw-bold small">
													Save <?php echo $this->App->priceOfferInfo($productCartValue, $productCartMRPValue); ?>
												</div>
											<?php endif; ?>
										</div>
									</div>
									<div class="mt-2">
										<div class="small">Quantity: </div>
										<div class="d-flex d-block col-sm-12 col-md-5 col-lg-4">

											<input
													type="number"
													id="ProductQuantity<?= $shoppingCartProductID ?>"
													name="data[Product][quantity]"
													class="form-control form-control-sm"
													min="1"
													max="100"
													data-shopping-cart-product-id="<?= $shoppingCartProductID ?>"
													data-actual-qty="<?= $qty ?>"
													value="<?= $qty ?>"
													required
											>
											<div>
												<button class="btn btn-sm btn-primary ms-2">Update</button>
											</div>

										</div>
									</div>
								</div>
							</div>

							<script>
								var app = new Vue({
									el: '#vueTopNavCartRow<?php echo $categoryID .'-'. $productID; ?>',
									data: {
										showUpdateCartDiv: false,
										productQty: <?= $qty ?>,
										showDeleteButton: parseInt('<?= $qty ?>') > 1 ? false : true,
									},
									watch: {
										productQty: function () {
											console.log(this.productQty);
											if (1 < parseInt(this.productQty)) {
												this.showDeleteButton = false;
											} else {
												this.showDeleteButton = true;
											}
										}
									},
									methods: {
										reduceProductQty: function() {
											this.productQty = parseInt(this.productQty) - 1;
										},
										increaseProductQty: function() {
											this.productQty = parseInt(this.productQty) + 1;
										},
										showUpdateCart: function (elementToBeFocused) {
											this.showUpdateCartDiv = true;
											this.$nextTick(() => $('#' + elementToBeFocused).focus());
										},
										showProductDetails: function (categoryId, productId) {
											let data;
											let productDetailsUrl;
											let myModal = new bootstrap.Modal(document.getElementById('productModal' + productId), {
												keyboard: false
											});
											myModal.show();

											productDetailsUrl = '/products/getDetails/' + categoryId + '/' + productId;
											data = getPage(productDetailsUrl);
											data.then(function (response) {
												$("#productModal" + productId + " .modal-body").html(response);
											});
										}
									}
								})
							</script>
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
					<a href="#topNavCart"
					   data-bs-toggle="collapse"
					   data-bs-target="#topNavCart"
					   aria-controls="topNavCart"
					   aria-expanded="false"
					   aria-label="Toggle navigation"
					   class="small"
					>
						Hide Cart
					</a>
				</div>
			</div>
		</div>
	</div>

	<?php
} else {
	?>
	<?php
}
?>

