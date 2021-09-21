<h1>Manage Category/Products</h1>

<div class="mt-4">
	<?= $this->element('products_quota_widget') ?>
</div>

<div class="text-end mt-4">
	<button
		id="addCategoryButton"
		type="button"
		class="btn btn-sm btn-primary"
		onclick="$('#categoryForm').toggleClass('d-none'); $('#addCategoryButton').toggleClass('d-none')"
	>
		+ Add Category
	</button>
</div>

<div id="categoryForm" class="d-none alert alert-warning">
	<div class="row">
		<div class="col-xs-12 col-md-10 col-lg-8">
			<?php echo $this->Form->create('', ['url' => '/admin/categories/add']); ?>

			<label for="CategoryName" class="form-label">Add New Category</label>
			<input
				type="text"
				name="data[Category][name]"
				id="CategoryName"
				class="form-control form-control-sm"
				placeholder="Enter category name"
				minlength="2"
				required
			>

			<div class='small text-muted mt-1'> Note*: Only alphanumeric characters are accepted. Special characters will be removed.</div>

			<div class="d-inline-block mt-3">
				<button type="submit" class="btn btn-primary btn-sm">Submit</button>
				<a href="#" class="ms-3" onclick="$('#categoryForm').toggleClass('d-none'); $('#addCategoryButton').toggleClass('d-none')">Cancel</a>

			</div>

			<?php echo $this->Form->end(); ?>
		</div>
	</div>

</div>

<div class="mt-3">

	<?php


	if (!empty($categories)) {
		?>
		<div class="table-responsive mt-3">
			<table class="table table-sm small">
				<thead>
				<tr>
					<th>#</th>
					<th>Image</th>
					<th>Category</th>
					<th>Status</th>
					<th></th>
				</tr>
				</thead>
				<tbody>
				<?php
				$k = 0;
				foreach ($categories as $row) {
					$k++;
					$categoryID = $row['Category']['id'];
					$categoryActive = $row['Category']['active'];
					$categoryName = Inflector::humanize($row['Category']['name']);
					$tmp = substr($categoryName, 0, 25);
					$categoryDisplayName = (strlen($categoryName) > 28) ? $tmp . '...' : $categoryName;
					$categoryNameSlug = Inflector::slug($categoryName, '-');

					$categoryUploadedImages = $row['Category']['images'] ? json_decode($row['Category']['images']) : [];
					$assetDomainUrl = Configure::read('AssetDomainUrl');
					$categoryHighlightImage = $this->App->getHighlightImage($categoryUploadedImages);

					$imageUrl = '';
					if ($categoryHighlightImage) {
						$image = $categoryHighlightImage['thumb'];
						$imageUrl = $assetDomainUrl.$image->imagePath;
					}

					?>
						<tr>
							<td><?= $k ?>.</td>

							<td>
								<?php
								if ($imageUrl) {
									?>
									<a href='/admin/categories/showProducts/<?= $categoryID ?>'>
										<img src="<?= $imageUrl ?> " loading="lazy" width="50" height="50" class="mb-2">
									</a>
									<?php
								}
								?>
							</td>
							<td>
								<?php if($categoryActive): ?>
									<span class="text-success" title="Active"><i class="fa fa-circle"></i></span>
								<?php else: ?>
									<span class="text-danger" title="Inactive"><i class="fa fa-circle"></i></span>
								<?php endif; ?>

								<a href="/admin/categories/showProducts/<?= $categoryID ?>" class="ms-1"><?= $categoryDisplayName ?></a>

							</td>
							<td>

							</td>
							<td class="text-end text-nowrap">
								<a href="/admin/categories/showProducts/<?= $categoryID ?>" class="btn btn-sm btn-primary">Manage Products</a>

								<div class="dropdown d-inline">
									<a class="p-2 ms-2" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
										<i class="fa fa-ellipsis-v p-2"></i>
									</a>

									<ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
										<li><a class="dropdown-item" href="/admin/categories/edit/<?= $categoryID ?>">Edit</a></li>
										<li><hr class="dropdown-divider"></li>
										<li>
											<a
													class="dropdown-item"
													href="#"
													onclick="showConfirmPopup('/admin/categories/delete/<?= $categoryID ?>', 'Delete Category', '*Note: All products in this category will also get deleted. <br><br>Are you sure you want to delete this category?'); return false;">
												Delete
											</a>
										</li>
									</ul>
								</div>
							</td>
						</tr>
					<?php
				}
				?>
				</tbody>
			</table>
		</div>
		<?php
	} else {
		echo '<p>No Category Found. Create a new category to add products.</p>';
	}
	?>
</div>
<br><br>
