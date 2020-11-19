<?php
$enableLightbox = true;
$assetDomainUrl = Configure::read('AssetDomainUrl');
?>
<!doctype html>
<html lang="en">
<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- Bootstrap CSS -->
	<link
		rel="stylesheet"
		href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha2/css/bootstrap.min.css"
		crossorigin="anonymous">

	<title>Seller</title>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

	<?php
	if (isset($loadVueJs) && $loadVueJs == true) {
		?>
		<!-- development version, includes helpful console warnings -->
		<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>

		<!-- production version, optimized for size and speed -->
		<!--		<script src="https://cdn.jsdelivr.net/npm/vue"></script>	-->
		<?php
	}
	?>
</head>

<body>

<nav class="navbar navbar-dark bg-info active bg-gradient">
	<div class="container">
		<a class="navbar-brand text-truncate" href="#"><?php echo $this->Session->read('Site.title'); ?></a>
	</div>
</nav>

<nav class="navbar navbar-expand-lg navbar-dark navbar-static bg-dark ">
	<div class="container">
		<div class="navbar-toggler border-0 p-1 py-0 text-white" type="button" data-toggle="collapse" data-target="#navbarNav"
			 aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
			<span class="fa fa-bars"></span> Admin
		</div>
		<a class="navbar-brand" href="/admin/sites/home">
			Home
		</a>
		<div class="collapse navbar-collapse" id="navbarNav">
			<ul class="navbar-nav ml-auto">
				<?php
				if ($this->App->isSeller()) {
					?>

					<li class="nav-item">
						<a class="nav-link" href="/users/setView/buyer"><i class="fa fa-sign-out-alt"></i>Customer View</a>
					</li>

					<li class="nav-item">
						<a class="nav-link" href="/users/myaccount"><i class="fa fa-sign-out-alt"></i>My Account</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="/users/logout"><i class="fa fa-sign-out-alt"></i> Logout</a>
					</li>
					<?php
				} else { ?>
					<li class="nav-item">
						<a class="nav-link" href="/users/login"><i class="fa fa-sign-in-alt"></i> Login</a>
					</li>
				<?php } ?>
			</ul>
		</div>
	</div>
</nav>

<div class="bg-light border-bottom">
	<ul class="nav container justify-content-start">
		<li class="nav-item productSideBar">
			<a class="nav-link font-weight-bold" href="/admin/categories/">Manage Products</a>
		</li>
		<li class="nav-item">
			<a class="nav-link font-weight-bold" href="#">Manage Orders</a>
		</li>
		<li class="nav-item">
			<a class="nav-link font-weight-bold" href="/admin/sites/settings">Store Settings</a>
		</li>
	</ul>
</div>
<!-- Navigation -->
<?php
//debug($this->Session->read());
?>
<div class="container">
	<?php echo $this->Session->flash(); ?>
</div>

<div class="container mt-3">
	<?php echo $this->fetch('content'); ?>
</div>

<div class="container">
	<!-- footer -->
</div>

<!-- Modal -->
<div class="modal fade" id="confirmPopup" data-backdrop="static" data-keyboard="false" tabindex="-1"
	 aria-labelledby="deleteModal" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="deleteModal"></h5>
				<button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="content">Are you sure?</div>
			</div>
			<div class="modal-footer mt-2 p-1">
				<a href="#" class="actionLink btn btn-danger btn-sm mr-2"><span class="ok">Ok</span></a>
				<button type="button" class="actionLinkButton btn btn-danger btn-sm mr-2" data-dismiss="modal"><span
						class="ok">Ok</span></button>
				<button type="button" class="btn btn-outline-secondary btn-sm cancelButton" data-dismiss="modal">
					Cancel
				</button>
			</div>
		</div>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="deleteImagePopup" data-backdrop="static" data-keyboard="false" tabindex="-1"
	 aria-labelledby="deleteModal" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="deleteModal">Delete</h5>
				<button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="content">Are you sure you want to delete it?</div>
			</div>
			<div class="modal-footer mt-2 p-1">
				<a href="#" class="deleteLink btn btn-danger btn-sm mr-2"><span class="ok">Ok</span></a>
				<button type="button" class="btn btn-outline-secondary btn-sm" data-dismiss="modal">Cancel</button>
			</div>
		</div>
	</div>
</div>

<!-- Optional JavaScript -->
<!-- Popper.js first, then Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"
		integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN"
		crossorigin="anonymous"></script>

<?php
if (isset($enableTextEditor) && $enableTextEditor) {
	?>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"
			integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV"
			crossorigin="anonymous"></script>
<?php
} else {
?>
	<script
		src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha2/js/bootstrap.min.js"
		integrity="sha384-5h4UG+6GOuV9qXh6HqOLwZMY4mnLPraeTrjT5v07o347pj6IkfuoASuGBhfDsp3d"
		crossorigin="anonymous"></script>
	<?php
}
?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.lazyload/1.9.1/jquery.lazyload.min.js"></script>

<script>
	// lazy load images
	if ($("img.lazy").length) {
		$("img.lazy").lazyload({
			effect: "fadeIn"
		});
	}

	// bootstrap tooltip
	var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-toggle="tooltip"]'))
	var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
		return new bootstrap.Tooltip(tooltipTriggerEl)
	})

	// custom js
	$(document).ready(function () {

		// Open navbarSide when button is clicked
		$('.productSideBar').on('click', function () {
			$('#navbarSide').addClass('reveal');
			$('.overlay').show();
		});

		// Close navbarSide when the outside of menu is clicked
		$('.overlay').on('click', function () {
			$('#navbarSide').removeClass('reveal');
			$('.overlay').hide();
		});

	});

	function formatPrice($value) {
		return '&#8377;'.$value;
	}

	// generate random number
	function getRndInteger(min, max) {
		return Math.floor(Math.random() * (max - min + 1)) + min;
	}

	// show delete popup
	function showConfirmPopup(url, title = '', content = '', okText = '') {

		var confirmPopup;
		title = title ? title : '';
		content = content ? content : 'Are you sure?';
		okText = okText ? okText : 'Ok';

		$("#confirmPopup .modal-content .modal-header .modal-title").html(title);
		$("#confirmPopup .modal-content .modal-body .content").html(content);
		$("#confirmPopup .modal-footer .ok").html(okText);

		$("#confirmPopup .modal-content .modal-header").show();
		if (title == '') {
			$("#confirmPopup .modal-content .modal-header").hide();
		}

		if ('#' !== url) {
			$("#confirmPopup .modal-content .actionLink").attr('href', url);
			$("#confirmPopup .modal-content .actionLink").removeClass('d-none');
			$("#confirmPopup .modal-content .cancelButton").removeClass('d-none');
			$("#confirmPopup .modal-content .actionLinkButton").addClass('d-none');
		} else {
			$("#confirmPopup .modal-content .actionLink").addClass('d-none');
			$("#confirmPopup .modal-content .cancelButton").addClass('d-none');
			$("#confirmPopup .modal-content .actionLinkButton").removeClass('d-none');
		}

		confirmPopup = new bootstrap.Modal(document.getElementById('confirmPopup'));
		confirmPopup.show();
	}

	// show delete popup
	function showDeleteImagePopup(deleteImageUrl, deleteImageActionUrl, title = '', content = '', okText = '') {
		var deletePopup;
		title = title ? title : '';
		content = content ? content : 'Are you sure you want to delete it?';
		okText = okText ? okText : 'Ok';

		$("#deleteImagePopup .modal-content .modal-header .modal-title").html(title);
		$("#deleteImagePopup .modal-content .modal-body .content").html(content);
		$("#deleteImagePopup .modal-footer .ok").html(okText);

		if (title == '') {
			$("#deleteImagePopup .modal-content .modal-header").hide();
		}

		// $("#deleteImagePopup .modal-content .deleteLink").attr('href', deleteUrl);

		$('#deleteImagePopup .modal-footer .deleteLink').on('click', function (event) {
			getData(deleteImageUrl).then(
				function (response) {
					if (response.error) {
						alert('error');
						return;
					}

					// $("#deleteImagePopup .modal-content .deleteLink").attr('href', deleteUrl);
					window.location.href = deleteImageActionUrl;


					console.log(response);
				})
		})

		deletePopup = new bootstrap.Modal(document.getElementById('deleteImagePopup'));
		deletePopup.show();
	}

	// POST method implementation:
	async function postData(url = '', data = {}) {
		// Default options are marked with *
		const response = await fetch(url, {
			method: 'POST', // *GET, POST, PUT, DELETE, etc.
			mode: 'cors', // no-cors, *cors, same-origin
			cache: 'no-cache', // *default, no-cache, reload, force-cache, only-if-cached
			credentials: 'same-origin', // include, *same-origin, omit
			headers: {
				'Content-Type': 'application/json'
				// 'Content-Type': 'application/x-www-form-urlencoded',
			},
			redirect: 'follow', // manual, *follow, error
			referrerPolicy: 'no-referrer', // no-referrer, *no-referrer-when-downgrade, origin, origin-when-cross-origin, same-origin, strict-origin, strict-origin-when-cross-origin, unsafe-url
			body: JSON.stringify(data) // body data type must match "Content-Type" header
		});
		return response.json(); // parses JSON response into native JavaScript objects
	}

	// GET page implementation:
	async function getPage(url = '', data = {}) {
		const response = await fetch(url + '?isAjax=1');

		return response.text(); // parses into html
	}

	// GET data implementation:
	async function getData(url = '', data = {}) {
		const response = await fetch(url);

		return response.json(); // parses JSON response into native JavaScript objects
	}
</script>

<!-- images zoom in - lightbox -->
<?php
if (isset($enableLightbox) && $enableLightbox) {
	?>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css"
		  integrity="sha512-ZKX+BvQihRJPA8CROKBhDNvoc2aDMOdAlcm7TUQY+35XYtrd3yh95QOOhsPDQY9QnKE0Wqag9y38OIgEvb88cA=="
		  crossorigin="anonymous"/>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"
			integrity="sha512-k2GFCTbp9rQU412BStrcD/rlwv1PYec9SNrkbQlo6RZCf75l6KcC3UwDY8H5n5hl4v77IDtIPwOk9Dqjs/mMBQ=="
			crossorigin="anonymous"></script>

	<script>
		$(document).ready(function () {
			if ($('#productImages a').length) {
				//$('#productImages a').lightBox();
			}
			if ($('#photogallery').length) {
				$('#photogallery').attr('class', 'active');
			}
			if ($('#contentImages a').length) {
				//$('#contentImages a').lightBox();
			}
		});
	</script>
	<?php
}
?>

<?php
// enable text editor
if (isset($enableTextEditor) && $enableTextEditor) {
	echo $this->element('text_editor');
}
?>

<?php
if (isset($enableImageCropper) && $enableImageCropper == true) {
	?>
	<script src="/croppie/croppie.js"></script>
	<link rel="stylesheet" href="/croppie/croppie.css"/>

	<script>
		$(document).ready(function () {
			let updateProductImage;
			const productImageUpdateUrl = '/admin/products/updateImage';
			const imageUploadUrl = "<?php echo $assetDomainUrl; ?>upload.php";
			const imageUploadRelPathDefault = '/<?php echo $this->Session->read('Site.id');?>/unknown';
			let imageUploadProductId = "";


			updateProductImage = function (productId, imagePath, type, commonId, reload = false) {
				if (!commonId) {
					commonId = getRndInteger(1, 10000);
				}

				$.ajax({
					url: productImageUpdateUrl + '/' + productId,
					type: "PUT",
					data: {
						"imagePath": imagePath,
						"imageType": type,
						"commonId": commonId
					},
					success: function (data) {
						console.log('image uploaded - ' + imagePath);

						if (reload) {
							location.reload();
						}
					}
				});
			};


			$image_crop = $('#image_preview').croppie({
				enableExif: true,
				enableResize: false,
				viewport: {
					width: 200,
					height: 200,
					type: 'square' //circle
				},
				boundary: {
					width: 300,
					height: 300
				},
			});

			$('#upload_image').on('change', function () {
				var reader = new FileReader();
				reader.onload = function (event) {
					$image_crop.croppie('bind', {
						url: event.target.result
					}).then(function () {
						console.log('jQuery bind complete');
					});
				}
				reader.readAsDataURL(this.files[0]);
				$('#uploadimageModal').modal('show');
			});


			$('.crop_image').click(function (event) {
				let imageUploadRelPath = imageUploadRelPathDefault;
				let commonId = getRndInteger(1, 10000);


				if ($('#upload_image').data('imageRelPath')) {
					imageUploadRelPath = $('#upload_image').data('imageRelPath');
				}

				if ($('#upload_image').data('productId')) {
					imageUploadProductId = $('#upload_image').data('productId');
				}

				$("#imageUploadProcessingDiv").removeClass("d-none");
				$("#imageUploadProcessingDiv span").text("1. Uploading large image");

				$(".imageUploadError").addClass("d-none");
				$(".imageUploadError").text("");

				$image_crop.croppie('result', {
					type: 'canvas',
					size: 'original', // can be "viewport" or {"width":800, "height":500},
					format: 'webp',
				}).then(function (response) {
					$.ajax({
						url: imageUploadUrl,
						type: "POST",
						data: {
							"image": response,
							"type": "ori",
							"image_name": imageUploadProductId,
							"relative_path": imageUploadRelPath
						},
						success: function (data) {
							let responseImagePath = data.imagePath;

							updateProductImage(imageUploadProductId, responseImagePath, "ori", commonId);

							$("#imageUploadProcessingDiv span").text("2. Uploading thumbnail image");

							$image_crop.croppie('result', {
								type: 'canvas',
								size: {"width": 200, "height": 200},
								format: 'webp',
							}).then(function (response) {
								$.ajax({
									url: imageUploadUrl,
									type: "POST",
									data: {
										"image": response,
										"type": "thumb",
										"image_name": imageUploadProductId,
										"relative_path": imageUploadRelPath
									},
									success: function (data) {
										responseImagePath = data.imagePath;
										updateProductImage(imageUploadProductId, responseImagePath, "thumb", commonId, true);

										$("#imageUploadProcessingDiv").addClass("d-none");
										$("#imageUploadProcessingDiv span").text("");


										$('#uploadimageModal').modal('hide');
										$('#uploaded_image').html(data);
									}
								});
							})
						},
						error: function (jqXHR, exception) {
							var msg = '';
							if (jqXHR.status === 0) {
								msg = 'Not connect.\n Verify Network.';
							} else if (jqXHR.status == 404) {
								msg = 'Requested page not found. [404]';
							} else if (jqXHR.status == 500) {
								msg = 'Internal Server Error [500].';
							} else if (exception === 'parsererror') {
								msg = 'Requested JSON parse failed.';
							} else if (exception === 'timeout') {
								msg = 'Time out error.';
							} else if (exception === 'abort') {
								msg = 'Request aborted.';
							} else {
								msg = 'Uncaught Error.\n' + jqXHR.responseText;
							}

							$("#imageUploadProcessingDiv").addClass("d-none");
							$("#imageUploadProcessingDiv span").text("");

							$("#imageUploadError").removeClass("d-none");
							$("#imageUploadError").text(msg);
						}

					});
				})
			});

		});
	</script>
	<?php
	echo $this->element('sql_dump');
}
?>
<script src="https://kit.fontawesome.com/231b614f56.js" crossorigin="anonymous" async></script>
</body>
</html>
