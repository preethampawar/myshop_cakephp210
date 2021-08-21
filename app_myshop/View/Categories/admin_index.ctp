<div class="my-3 d-flex justify-content-between align-items-center">
	<h5>Manage Products</h5>
	<button id="addCategoryButton" type="button" class="btn btn-sm btn-info" onclick="$('#categoryForm').toggleClass('d-none'); $('#addCategoryButton').toggleClass('d-none')">+ Add Category</button>
</div>

<div id="categoryForm" class="d-none bg-light border border-secondary rounded p-2">
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

<div class="mt-4">
	<?= $this->element('products_quota_widget') ?>
</div>

<div class="mt-4">

	<h6>Select Category</h6>
	<?php
	App::uses('Category', 'Model');
	$categoryModel = new Category;
	$categories = $categoryModel->admin_getCategories();

	if (!empty($categories)) {
		?>
		<div id='adminCategoryNavigation'>
			<ul class="list-group">
				<?php
				foreach ($categories as $row) {
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
					<li class="list-group-item d-flex justify-content-between align-items-center px-2 py-3">
						<div>
							<?php if($categoryActive): ?>
								<span class="small fa fa-circle text-success" title="Active"></span>
							<?php else: ?>
								<span class="small fa fa-circle text-danger" title="Inactive"></span>
							<?php endif; ?>

							<?php echo $this->Html->link($categoryDisplayName, '/admin/categories/showProducts/' . $categoryID . '/' . $categoryNameSlug, ['title' => $categoryName, 'class' => 'floatLeft', 'style' => 'width:180px;']); ?>
						</div>

						<div>
							<?php
							if ($imageUrl) {
								?>
								<a href='/admin/categories/showProducts/<?= $categoryID ?>'>
									<img src="<?= $imageUrl ?> " loading="lazy" width="100" height="100" class="mb-2">
								</a>
								<?php
							}
							?>
						</div>

						<div>
							<a href="/admin/categories/edit/<?= $categoryID ?>" class="btn btn-sm btn-primary">Edit</a>

							<?php
							/*
							$confirmMessage = 'Deleting this category will delete all the category information and products associated with it. This action is irreversible. <br><br> Are you sure you want to delete this category?';
							$url = '/admin/categories/delete/' . $categoryID;
							$title = 'Delete - '. $categoryName;
							?>
							<span
								class="far fa-trash-alt ms-2 text-danger"
							 	onclick="showConfirmPopup('<?php echo $url;?>', '<?php echo $title;?>', '<?php echo $confirmMessage;?>')"></span>
							<?php
							*/
							?>
						</div>
					</li>
					<?php
				}
				?>
			</ul>
		</div>
		<?php
	} else {
		echo '<p>No Category Found. Create a new category to add products.</p>';
	}
	?>
</div>
<br><br>
