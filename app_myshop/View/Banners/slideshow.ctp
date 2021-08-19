<?php
$slideShowImages = [];

if ($banners) {
	$assetDomainUrl = Configure::read('AssetDomainUrl');
	$i = 0;
	foreach ($banners as $banner) {

		$bannerId = $banner['Banner']['id'];
		$title = $banner['Banner']['title'];
		$description = $banner['Banner']['description'];
		$url = $banner['Banner']['url'];
		$bannerUploadedImages = $banner['Banner']['images'] ? json_decode($banner['Banner']['images']) : [];
		$highlightImage = $this->App->getHighlightImage($bannerUploadedImages);

		if ($highlightImage) {
			$image = $highlightImage['ori'];
			$imageUrl = $assetDomainUrl.$image->imagePath;

			$slideShowImages[$i]['bannerId'] = $banner['Banner']['id'];
			$slideShowImages[$i]['title'] = htmlentities(trim($banner['Banner']['title']));
			$slideShowImages[$i]['description'] = htmlentities(trim($banner['Banner']['description']));
			$slideShowImages[$i]['linkUrl'] = $banner['Banner']['url'];
			$slideShowImages[$i]['imageUrl'] = $imageUrl;
		}
		$i++;
	}
}

if ($slideShowImages) {
?>

<div class="mb-5">
	<div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
		<div class="carousel-indicators">
			<?php
			$i = 0;
			foreach($slideShowImages  as $row) {
			?>
				<button
						type="button"
						data-bs-target="#carouselExampleCaptions"
						data-bs-slide-to="<?=$i?>"
						<?= $i === 0 ? 'class="active"' : '' ?>
						aria-current="true"
						aria-label="Slide <?=$i?>"></button>
			<?php
				$i++;
			}
			?>
		</div>
		<div class="carousel-inner">

			<?php
			$i = 0;
			foreach($slideShowImages  as $row) {
				$bannerId = $row['bannerId'];
				$title = $row['title'];
				$desc = $row['description'];
				$linkUrl = $row['linkUrl'];
				$imageUrl = $row['imageUrl'];
				?>
				<div class="carousel-item <?= $i === 0 ? 'active' : '' ?>">
					<img src="<?= $imageUrl ?>" class="d-block w-100" alt="">
					<div class="carousel-caption d-none d-md-block">
						<?php if ($title) { ?>
							<span class="bg-light text-dark shadow-sm rounded px-2 fw-bold fs-5">
								<?php if ($linkUrl) { ?>
									<a href="<?= $linkUrl ?>" title="<?= $title ?>" class="text-decoration-none"><?= $title ?></a>
								<?php } else {
									?>
									<?= $title ?>
									<?php
								} ?>

							</span>
						<?php } ?>
						<p><?php if ($desc) { ?><span class="bg-light text-dark shadow-sm rounded px-2"><?= $desc ?><?php } ?></span></p>
					</div>
				</div>
				<?php
				$i++;
			}
			?>

		</div>
		<button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
			<span class="carousel-control-prev-icon" aria-hidden="true"></span>
			<span class="visually-hidden">Previous</span>
		</button>
		<button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
			<span class="carousel-control-next-icon" aria-hidden="true"></span>
			<span class="visually-hidden">Next</span>
		</button>
	</div>
</div>

<?php } ?>
