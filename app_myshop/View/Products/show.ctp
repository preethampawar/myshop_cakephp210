<?php
$this->set('title_for_layout', $categoryInfo['Category']['name']);
?>
<section id="ProductInfo">
	<article>
		<header>
			<h1><?php echo ucwords($categoryInfo['Category']['name']); ?></h1>
			<p class="text-muted mb-4"><?= $categoryInfo['Category']['description'] ?></p>
		</header>
		<?php
		$categoryProducts = $categoryInfo['CategoryProducts'];

		if (!empty($categoryProducts)) {
			?>

			<div class="row row-cols-2 row-cols-sm-3 row-cols-sm-3 row-cols-md-4 row-cols-lg-5 row-cols-xl-6 g-3 g-lg-x-4 p-0">

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
					$noStock = $row2['Product']['no_stock'];
					$cartEnabled = $this->Session->read('Site.shopping_cart');
					$hideProductPrice = $row2['Product']['hide_price'];

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
<br>


<?php
$categoryUploadedImages = $categoryInfo['Category']['images'] ? json_decode($categoryInfo['Category']['images']) : [];
$assetDomainUrl = Configure::read('AssetDomainUrl');
$categoryHighlightImage = $this->App->getHighlightImage($categoryUploadedImages);
$pageUrl = $this->Html->url($this->request->here, true);
$categoryDesc = $categoryInfo['Category']['description'];
$categoryName = $categoryInfo['Category']['name'];

$imageUrl = '';
if ($categoryHighlightImage) {
	$image = $categoryHighlightImage['thumb'];
	$imageUrl = $this->Html->url($assetDomainUrl.$image->imagePath, true);
}

$customMeta = '';
$customMeta .= $this->Html->meta(['property' => 'og:url', 'content' => $pageUrl, 'inline' => false]);
$customMeta .= $this->Html->meta(['property' => 'og:type', 'content' => 'product', 'inline' => false]);
$customMeta .= $this->Html->meta(['property' => 'og:title', 'content' => strip_tags($categoryName), 'inline' => false]);
$customMeta .= $this->Html->meta(['property' => 'og:description', 'content' => strip_tags(trim($categoryDesc) == '' ? $categoryName : $categoryDesc), 'inline' => false]);
$customMeta .= ($imageUrl) ? $this->Html->meta(['property' => 'og:image', 'content' => $imageUrl, 'inline' => false]) : '';
$customMeta .= $this->Html->meta(['property' => 'og:site_name', 'content' => $this->Session->read('Site.title'), 'inline' => false]);

$this->set('customMeta', $customMeta);
$this->set('title_for_layout', $categoryName);

$metaKeywords = trim($categoryInfo['Category']['meta_keywords']) != '' ? $categoryInfo['Category']['meta_keywords'] : $categoryName;
$metaDesc = trim($categoryInfo['Category']['meta_description']) != '' ? $categoryInfo['Category']['meta_description'] : $categoryDesc;

if (trim($metaKeywords)){
	$this->Html->meta('keywords', strip_tags($metaKeywords), ['inline' => false]);
}

if (trim($metaDesc)) {
	$this->Html->meta('description', strip_tags($metaDesc), ['inline' => false]);
}
?>
