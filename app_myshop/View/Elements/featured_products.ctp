<?php
$limit = $limit ?? 0;
$homepage = $homepage ?? null;

App::uses('Product', 'Model');
$productModel = new Product();
$allCategories = $productModel->getAllProducts($this->Session->read('Site.id'), true, $limit);
?>

<section id="ProductsInfo">
	<article>
		<?= $this->element('homepage_tabmenu', ['homepage' => $homepage]) ?>

		<?php
		if (!$homepage) {
		?>
			<h1 class="mt-2"><i class="fa fa-fire text-orange"></i> Hot Deals (<?= count($allCategories) ?> items)</h1>
		<?php
		}
		?>

		<?php
		if (!empty($allCategories)) {
			$pCount = 0;
			$categoriesCount = count($allCategories);
			$assetDomainUrl = Configure::read('AssetDomainUrl');

			$showOneProductOnSmallScreen = Configure::read('ShowOneProductOnSmallScreen') ?? false;
			$productsRowClass = "row g-3 g-lg-x-4 p-0";
			if ($showOneProductOnSmallScreen) {
				$productsRowClass = "row g-3 g-lg-x-4 p-0";
			}
		?>
			<div class="row g-3 g-lg-x-4 p-0 mt-3">
				<?php
				foreach ($allCategories as $row) {
					$categoryID = $row['Category']['id'];
					$categoryName = $row['Category']['name'];
					$categoryNameSlug = Inflector::slug($categoryName, '-');

					$pCount++;
					$productID = $row['Product']['id'];
					$productName = $row['Product']['name'];
					$productShortDesc = $row['Product']['short_desc'];
					$productNameSlug = Inflector::slug($productName, '-');
					$productTitle = $productName;


					$productUploadedImages = $row['Product']['images'] ? json_decode($row['Product']['images']) : [];
					$imageDetails = $this->App->getHighlightImage($productUploadedImages);
					$thumbUrl = "/img/noimage.jpg";
					$imageTagId = random_int(1, 10000);

					if ($imageDetails) {
						$thumbUrl = $assetDomainUrl . $imageDetails['thumb']->imagePath;
					}

					$productImageUrl = $thumbUrl;
					$mrp = $row['Product']['mrp'];
					$discount = $row['Product']['discount'];
					$salePrice = $mrp - $discount;
					$noStock = $row['Product']['no_stock'];
					$cartEnabled = $this->Session->read('Site.shopping_cart');
					$hideProductPrice = $row['Product']['hide_price'];
					$avgRating = $row['Product']['avg_rating'];
					$ratingsCount = $row['Product']['ratings_count'];
				?>

					<div class="col-6 col-md-4 col-lg-3 col-xl-2 mb-3" id="productCard<?php echo $categoryID . '-' . $productID; ?>">
						<?php
						echo $this->element(
							'product_card',
							[
								'productImageUrl' => $productImageUrl,
								'productName' => $productName,
								'productShortDesc' => $productShortDesc,
								'imageTagId' => $imageTagId,
								'productTitle' => $productTitle,
								'categoryID' => $categoryID,
								'productID' => $productID,
								'categoryNameSlug' => $categoryNameSlug,
								'productNameSlug' => $productNameSlug,
								'mrp' => $mrp,
								'discount' => $discount,
								'salePrice' => $salePrice,
								'cartEnabled' => $cartEnabled,
								'noStock' => $noStock,
								'hideProductPrice' => $hideProductPrice,
								'avgRating' => $avgRating,
								'ratingsCount' => $ratingsCount,
							]
						);
						?>
					</div>
				<?php
				}
				?>
			</div>

			<?php
			if ($homepage) {
			?>
				<div class="mt-5 mb-5 text-center">
					<a href="/products/showFeatured" class="btn btn-orange btn-sm">Show more deals...</a>
				</div>
				<hr>
			<?php
			}
			?>

		<?php
		} else {
		?>
			<p>No Products Found</p>
		<?php
		}
		?>
	</article>
</section>