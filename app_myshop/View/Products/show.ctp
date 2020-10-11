<section id="ProductInfo">
	<article>
		<header>
			<h2><?php echo ucwords($categoryInfo['Category']['name']); ?></h2>
		</header>
		<?php
		$categoryProducts = $categoryInfo['CategoryProducts'];

		if (!empty($categoryProducts)) {
			?>
			<div class="row row-cols-2 row-cols-md-4 row-cols-lg-5 row-cols-xl-6 g-3">

				<?php
				$categoryID = $categoryInfo['Category']['id'];
				$categoryName = ucwords($categoryInfo['Category']['name']);
				$categoryNameSlug = Inflector::slug($categoryName, '-');

				foreach ($categoryProducts as $row2) {
					$productID = $row2['Product']['id'];
					$productName = ucwords($row2['Product']['name']);
					$productNameSlug = Inflector::slug($productName, '-');
					$showRequestPriceQuote = $row2['Product']['request_price_quote'];
					$productTitle = $productName;
					$assetDomainUrl = Configure::read('AssetDomainUrl');
					$productUploadedImages = $row2['Product']['images'] ? json_decode($row2['Product']['images']) : [];
					$imageDetails = $this->App->getHighlightImage($productUploadedImages);
					$thumbUrl = "/img/noimage.jpg";
					$imageTagId = random_int(1, 10000);

					if($imageDetails) {
						$thumbUrl = $assetDomainUrl . $imageDetails['thumb']->imagePath;
					}

					$productImageUrl = $thumbUrl;
					$mrp = $row2['Product']['mrp'];
					$discount = $row2['Product']['discount'];
					$salePrice = $mrp - $discount;

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
						]
					);
				}
				?>
			</div>
			<?php
		} else {
			?>
			<p>No Products Found</p>
			<?php
		}
		?>

	</article>
</section>
