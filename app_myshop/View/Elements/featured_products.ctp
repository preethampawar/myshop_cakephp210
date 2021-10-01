<?php
$limit = $limit ?? 0;
$homepage = $homepage ?? null;

App::uses('Product', 'Model');
$productModel = new Product();
$allCategories = $productModel->getAllProducts($this->Session->read('Site.id'), true, $limit);

//$featuredProductsCacheKey = $this->Session->read('CacheKeys.featuredProducts');
//$allCategories = Cache::read($featuredProductsCacheKey, 'verylong');
?>

<section id="ProductsInfo">
	<article>
		<?php
		if ($homepage) {
		?>
		<header class="featuredLabel">
			<ul class="nav nav-tabs">
				<li class="nav-item">
					<a class="nav-link fw-bold active" aria-current="page" href="/">Best Deals</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="/products/showAll">Show All Products</a>
				</li>
			</ul>
			<?php //echo $this->Html->link('Best Deals', '/', ['class' => 'btn btn-primary btn-sm']); ?>
			<?php // echo $this->Html->link('Show All Products', '/products/showAll', ['class' => 'btn btn-outline-primary btn-sm ms-2']); ?>
		</header>
		<?php
		} else {
			?>
				<nav aria-label="breadcrumb" class="mb-4">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="/">Home</a></li>
						<li class="breadcrumb-item active" aria-current="page">Best Deals</li>
					</ol>
				</nav>
				<h1>Best Deals (<?= count($allCategories) ?> items)</h1>
			<?php
		}
		?>

		<?php
		if (!empty($allCategories)) {
			$pCount = 0;
			$categoriesCount = count($allCategories);
			$assetDomainUrl = Configure::read('AssetDomainUrl');

			$showOneProductOnSmallScreen = Configure::read('ShowOneProductOnSmallScreen') ?? false;
			$productsRowClass = "row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-5 row-cols-xl-6 g-3 g-lg-x-4 p-0";
			if ($showOneProductOnSmallScreen) {
				$productsRowClass = "row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 g-3 g-lg-x-4 p-0";
			}
			?>
			<div class="<?= $productsRowClass ?> mt-3">
				<?php
				foreach ($allCategories as $row) {
					$categoryID = $row['Category']['id'];
					$categoryName = ucwords($row['Category']['name']);
					$categoryNameSlug = Inflector::slug($categoryName, '-');

					$pCount++;
					$productID = $row['Product']['id'];
					$productName = ucwords($row['Product']['name']);
					$productNameSlug = Inflector::slug($productName, '-');
					$productTitle = $productName;


					$productUploadedImages = $row['Product']['images'] ? json_decode($row['Product']['images']) : [];
					$imageDetails = $this->App->getHighlightImage($productUploadedImages);
					$thumbUrl = "/img/noimage.jpg";
					$imageTagId = random_int(1, 10000);

					if($imageDetails) {
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

					echo $this->element('product_card', [
							'productImageUrl' => $productImageUrl,
							'productName' => $productName,
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


//					if (!empty($row['CategoryProducts'])) {
//						foreach ($row['CategoryProducts'] as $row2) {
//							$pCount++;
//							$productID = $row2['Product']['id'];
//							$productName = ucwords($row2['Product']['name']);
//							$productNameSlug = Inflector::slug($productName, '-');
//							$productTitle = $productName;
//
//							$assetDomainUrl = Configure::read('AssetDomainUrl');
//							$productUploadedImages = $row2['Product']['images'] ? json_decode($row2['Product']['images']) : [];
//							$imageDetails = $this->App->getHighlightImage($productUploadedImages);
//							$thumbUrl = "/img/noimage.jpg";
//							$imageTagId = random_int(1, 10000);
//
//							if($imageDetails) {
//								$thumbUrl = $assetDomainUrl . $imageDetails['thumb']->imagePath;
//							}
//
//							$productImageUrl = $thumbUrl;
//							$mrp = $row2['Product']['mrp'];
//							$discount = $row2['Product']['discount'];
//							$salePrice = $mrp - $discount;
//
//							echo $this->element('product_card', [
//									'productImageUrl' => $productImageUrl,
//									'productName' => $productName,
//									'imageTagId' => $imageTagId,
//									'productTitle' => $productTitle,
//									'categoryID' => $categoryID,
//									'productID' => $productID,
//									'categoryNameSlug' => $categoryNameSlug,
//									'productNameSlug' => $productNameSlug,
//									'mrp' => $mrp,
//									'discount' => $discount,
//									'salePrice' => $salePrice,
//								]
//							);
//						}
//					}
				}
				?>
			</div>

			<?php
			if ($homepage) {
				?>
				<div class="mt-4 mb-5 text-center">
					<a href="/products/showFeatured" class="btn btn-orange btn-sm">Show All Deals</a>
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
		<div class='clear'></div>
	</article>
</section>
