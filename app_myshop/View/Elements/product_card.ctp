<?php
$this->set('loadVueJs', false);
$selectBoxQuantityOptions = '';
for ($i = 1; $i <= 50; $i++) {
	$selectBoxQuantityOptions .= "<option value='$i'>$i</option>";
}

$showDiscount = $mrp != $salePrice;
$assetDomainUrl = Configure::read('AssetDomainUrl');
$loadingImageUrl = '/loading2.gif';
$productSlug = Inflector::slug($productTitle, '-');
$productDetailsPageUrl = '/products/getDetails/' . $categoryID . '/' . $productID . '/' . $productSlug;
// http://www.apnastores.com/assets/images/loading/loading.gif

$avgRating = $avgRating ?? 0;
$ratingsCount = $ratingsCount ?? 0;
?>

<div class="col mb-3 bg-white hoverHighlightPink" id="productCard<?php echo $categoryID . '-' . $productID; ?>">
	<div class="card h-100 shadow p-0 mb-1 text-dark border-0 hoverHighlightPink" id="productCard<?php echo $productID; ?>">

		<a href="<?= $productDetailsPageUrl ?>" class="text-decoration-underline">
			<img
				src="<?php echo $loadingImageUrl; ?>"
				data-original="<?php echo $productImageUrl; ?>"
				class="lazy w-100"
				role="button"
				alt="<?php echo $productName; ?>"
				id="<?php echo $imageTagId; ?>"
				width="200"
			/>
		</a>

		<div class="card-body p-2 pt-0 text-center">
			<a href="<?= $productDetailsPageUrl ?>" class="text-purple text-decoration-none">
				<h6 class="mt-3"><?php echo $productTitle; ?></h6>
			</a>

			<div class="mt-2 small text-center d-flex justify-content-center">
				<?= $this->element('show_rating_stars', ['rating' => $avgRating, 'count' => $ratingsCount]) ?>
			</div>

			<?php if (!$hideProductPrice): ?>
				<div class="mt-3 d-flex justify-content-between">
					<h5>
						<span class="text-danger"><?php echo $this->App->price($salePrice); ?></span>
					</h5>
					<?php if ($showDiscount): ?>
						<div class="ps-2">
							<span
								class="small text-decoration-line-through">MRP <?php echo $this->App->price($mrp); ?></span>
						</div>
					<?php endif; ?>
				</div>


				<?php if ($showDiscount): ?>
					<div class="small text-center">
						<span class="text-success">Save <?php echo $this->App->priceOfferInfo($salePrice, $mrp); ?></span>
					</div>
				<?php endif; ?>
			<?php endif; ?>
		</div>


		<?php if (!$hideProductPrice && $cartEnabled): ?>
			<div class="card-footer text-center border-top-0 pt-3 pb-3">
				<div class="card-text">
					<?php if (!$noStock): ?>
						<div class="text-center p-0">
							<button type="button" class="btn btn-sm btn-primary" onclick="showAddProductQtyModal('<?= $categoryID ?>', '<?= $productID ?>')">
								Add to cart
							</button>
						</div>
					<?php else: ?>
						<button type="button" class="btn btn-sm btn-outline-secondary disabled">Out of stock</button>
					<?php endif; ?>
				</div>
			</div>
		<?php endif; ?>
	</div>
</div>

