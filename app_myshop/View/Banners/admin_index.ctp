<section>
	<article>
		<?php
		if ((int)$this->Session->read('Site.show_banners') === 1) {
			?>
			<div class="alert alert-success">Banners slideshow on Homepage has been enabled. Go to Store Settings to disable slideshow.</div>
			<?php
		} else {
			?>
			<div class="alert alert-warning">Banners slideshow on Homepage has been disabled. Go to Store Settings to enable slideshow.</div>
			<?php
		}
		?>


		<header><h2>Banners</h2></header>
		<div class="text-end mt-3">
			<a href="/admin/banners/add/" class="btn btn-primary btn-sm">+ Add New Banner</a>
		</div>
		<div class="table-responsive">
			<?php
			if (!empty($banners)) {
				$i = 1;
				?>
				<table class="table">
					<thead>
					<tr>
						<th>#</th>
						<th>Image</th>
						<th>Banner Title</th>
						<th>Status</th>
						<th>Created on</th>
						<th></th>
					</tr>
					</thead>
					<tbody>
					<?php
					foreach ($banners as $row) {

						$bannerId = $row['Banner']['id'];
						$bannerTitle = $row['Banner']['title'];
						$blogCreatedOn = date('d/m/Y', strtotime($row['Banner']['created']));
						$bannerActive = $row['Banner']['active'];
						?>
						<tr>
							<td><?php echo $i; ?>.</td>
							<td>
								<?php
								$bannerUploadedImages = $row['Banner']['images'] ? json_decode($row['Banner']['images']) : [];
								$assetDomainUrl = Configure::read('AssetDomainUrl');
								$highlightImage = $this->App->getHighlightImage($bannerUploadedImages);

								if ($highlightImage) {
									$image = $highlightImage['thumb'];
									$imageUrl = $assetDomainUrl.$image->imagePath;
									?>
									<img src="<?= $imageUrl ?> " width="100" height="40" class="mb-2">
									<?php
								} else {
									echo 'n/a';
								}
								?>
							</td>
							<td>
								<?php
								echo $this->Html->link("<strong>$bannerTitle</strong>", '/admin/banners/edit/' . $bannerId, ['escape' => false, 'style' => 'text-decoration:none;']);
								?>
							</td>

							<td>
								<?php
								if ($bannerActive) {
									echo $this->Html->link('Active', '/admin/banners/activate/' . $bannerId . '/false', ['escape' => false, 'style' => 'color:green'], 'Are you sure you want to deactivate this article? Deactivating will hide this article from public.');
								} else {
									echo $this->Html->link('Inactive', '/admin/banners/activate/' . $bannerId . '/true', ['escape' => false, 'style' => 'color:red;'], 'Are you sure you want to make this article to public?');
								}
								?>
							</td>
							<td><?php echo $blogCreatedOn; ?></td>

							<td class="text-nowrap">
								<a href="/admin/banners/edit/<?= $bannerId ?>" class="btn btn-sm btn-primary">Edit</a>
								<button
									class="ms-2 btn btn-sm btn-outline-danger"
									type="button"
									onclick="showConfirmPopup('/admin/banners/delete/<?= $bannerId ?>', 'Delete Banner', 'Are you sure you want to delete this?')"
								>Delete</button>

							</td>
						</tr>
						<?php
						$i++;
					}
					?>
					</tbody>
				</table>
				<?php
			} else {
				echo "<br> - No banners found";
			}
			?>
		</div>
	</article>
</section>

