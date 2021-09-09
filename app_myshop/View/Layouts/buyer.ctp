<?php
$testimonialsEnabled = (int)$this->Session->read('Site.show_testimonials') === 1;
$theme = $this->Session->read('Theme');
$navbarTheme = $theme['navbarTheme'];
$secondaryMenuBg = $theme['secondaryMenuBg'];
$linkColor = $theme['linkColor'];
$cartBadgeBg = $theme['cartBadgeBg'];
$hightlightLink = $theme['hightlightLink'];

if (!empty($title_for_layout)) {
	$title_for_layout = $title_for_layout . ' - ' . $this->Session->read('Site.title');
} else {
	$siteCaption = $this->Session->read('Site.caption');
	$title_for_layout = $this->Session->read('Site.title');
	$title_for_layout .= (!empty($siteCaption)) ? ' - ' . $siteCaption : '';
}

$analyticsCode = null;
if (!empty(trim($this->Session->read('Site.analytics_code')))) {
	$analyticsCode = $this->Session->read('Site.analytics_code');
}

$logoUrl = null;
if ($this->Session->read('Site.logo')) {
	$logoUrl = $this->Html->url('/'.$this->Session->read('Site.logo'), true);
}

?>

<!doctype html>
<html lang="en">
<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<?php
	echo $this->fetch('meta');
	echo (isset($customMeta)) ? $customMeta : null;
	echo (isset($facebookMetaTags)) ? $facebookMetaTags : null;
	?>
	<title><?= $title_for_layout ?></title>
	<script>
		if (!window.fetch) {
			window.location = '/pages/unsupportedbrowser'
		}
	</script>

	<link rel="stylesheet" href="/vendor/bootstrap-5.1.0-dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="/vendor/lightbox2-2.11.3/dist/css/lightbox.min.css">
	<link rel="stylesheet" href="/vendor/fontawesome-free-5.15.3-web/css/all.min.css">
	<link rel="stylesheet" href="/css/site.css?v=1.0.3">
	<?= $this->element('customcss') ?>

	<script src="/vendor/jquery/jquery-3.6.0.min.js"></script>
	<?= $analyticsCode ?>

	<?php
	if (isset($loadVueJs) && $loadVueJs == true) {
		?>
		<script src="/vendor/vue/vue.min.js"></script>
		<?php
	}
	?>
</head>

<body class="bg-dark">
	<div class="bg-white ">
		<nav class="navbar navbar-expand-lg navbar-static <?= $navbarTheme ?>">
			<div class="container-fluid py-3">
				<a class="navbar-brand" href="/">
					<?php
					if ($logoUrl) {
						?>
							<img
								src="<?= $logoUrl ?>"
								alt="<?= $this->Session->read('Site.title') ?>"
								title="<?= $this->Session->read('Site.title') ?>"
								style="max-width: 250px;">
						<?php
					} else {
						?>
						<i class="fa fa-home"></i> <?= $this->Session->read('Site.title') ?>
						<?php
					}
					?>
				</a>

				<div class="navbar-toggler border-0 " type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
					<i class="fa fa-bars"></i>
				</div>

				<div class="collapse navbar-collapse" id="navbarNav">
					<ul class="navbar-nav ml-auto">
						<?php
						if ($this->App->isSellerForThisSite()) {
						?>
						<li class="nav-item px-1">
							<a class="nav-link px-1 <?= $hightlightLink ?> highlight-link" href="/users/setView/seller"><i class="fa fa-tools"></i> Manage Store</a>
						</li>
						<?php
						}
						?>

						<li class="nav-item px-1">
							<a class="nav-link px-1" href="/sites/about">About Us</a>
						</li>
						<li class="nav-item px-1">
							<a class="nav-link px-1" href="/sites/contact">Contact Us</a>
						</li>
						<li class="nav-item px-1">
							<a class="nav-link px-1" href="/sites/paymentInfo">Payment Details</a>
						</li>

						<?php if ($this->Session->check('User.id') && $this->Session->read('Site.shopping_cart')): ?>
							<li class="nav-item px-1">
								<a class="nav-link fw-normal px-1" href="/orders/">My Orders</a>
							</li>
						<?php endif; ?>

						<li class="nav-item px-1">
							<?php if ($this->Session->check('User.id')): ?>
								<a class="nav-link px-1" href="/users/logout">Logout</a>
							<?php else: ?>
								<a class="nav-link px-1" href="/users/login">Login</a>
							<?php endif; ?>
						</li>
						<?php if (!$this->Session->check('User.id')): ?>
							<li class="nav-item px-1">
								<a class="nav-link px-1" href="/users/customerRegistration">Register</a>
							</li>
						<?php endif; ?>
						<?php if ($this->Session->check('User.id')): ?>
							<li class="nav-item px-1">
								<?php
								//debug($this->Session->read('User'));
								?>
								<a class="nav-link <?= $hightlightLink ?>  highlight-link px-1 disabled" href="#">
									<i class="fa fa-user-circle"></i>
									<?= $this->Session->read('User.firstname')!= '' ? $this->Session->read('User.firstname') : $this->Session->read('User.mobile') ?>
								</a>
							</li>
						<?php endif; ?>
					</ul>
				</div>
			</div>
		</nav>

		<div class="sticky-top shadow-sm <?= $secondaryMenuBg ?>">
			<ul class="nav container-fluid justify-content-center pt-3 pb-3 small">
				<li class="nav-item">
					<a href="#" class="nav-link <?= $linkColor ?>" data-bs-toggle="offcanvas" data-bs-target="#categoriesMenu">
						<span class="fs-5"><i class="fa fa-th"></i></span> Shop By Category
					</a>
				</li>
				<?php if ($this->Session->read('Site.shopping_cart')): ?>
					<li class="nav-item" id="topNavShoppingCart">
						<a href="#" class="nav-link <?= $linkColor ?>" data-bs-toggle="offcanvas" data-bs-target="#myShoppingCart">
							<span class="fs-5"><i class="fa fa-shopping-cart"></i></span> My Cart <span class="badge rounded-pill <?= $cartBadgeBg ?>">0</span>
						</a>
					</li>
				<?php endif; ?>
			</ul>
		</div>

		<div id="storeSlideShow">
			<?= $this->element('banner_slideshow') ?>
		</div>

		<div class="container mt-5" style="min-height: 400px;">
			<?php echo $this->fetch('content'); ?>

			<div id="storeTestimonials" class="mt-4">
				<?= $this->element('testimonials_slideshow') ?>
			</div>

			<?php
			$showPaymentContactInfo = false;

			if ($this->request->params['controller'] != 'users'
					&& $this->request->params['controller'] != 'sites'
					&& $this->request->params['controller'] != 'orders') {
				$showPaymentContactInfo = true;
			}

			if ($showPaymentContactInfo && !empty($this->Session->read('Site.contact_info'))):
				?>

				<div class="text-center alert alert-info mt-4">
					<h4 class="text-decoration-underline">Contact Us</h4>
					<div class="small mt-4">
						<?= $this->Session->read('Site.contact_info') ?>
					</div>
				</div>
			<?php
			endif;
			?>

			<?php
			if ($showPaymentContactInfo && !empty($this->Session->read('Site.payment_info'))):
				?>

				<div class="text-center alert alert-info mt-4">
					<h4 class="text-decoration-underline">Payment Details</h4>
					<div class="small mt-4">
						<?= $this->Session->read('Site.payment_info') ?>
					</div>
				</div>
			<?php
			endif;
			?>

			<?= $this->element('showmap') ?>

			<!-- --------------------------End of visible content----------------------------- -->

			<!-- Categories Menu -->
			<div class="offcanvas offcanvas-start" tabindex="-1" id="categoriesMenu" aria-labelledby="offcanvasTopLabel">
				<div class="offcanvas-header border-bottom border-4 border-warning">
					<h5 id="offcanvasTopLabel">Shop By Category</h5>
					<button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
				</div>
				<div class="offcanvas-body" id="categoriesMenuBody">
					<?php echo $this->element('categories_menu'); ?>
					<div class="mt-4 text-center">
						<button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="offcanvas" aria-label="Close">Close</button>
					</div>
				</div>

			</div>

			<!-- Shopping Cart -->
			<div class="offcanvas offcanvas-end" tabindex="-1" id="myShoppingCart" aria-labelledby="offcanvasTopLabel">
				<div class="offcanvas-header border-bottom border-4 border-warning">
					<h5 id="offcanvasTopLabel"><i class="fa fa-shopping-cart"></i> My Cart</h5>
					<button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
				</div>
				<div class="offcanvas-body" id="myShoppingCartBody"></div>
			</div>

			<!-- Order Summary -->
			<div class="offcanvas offcanvas-end" tabindex="-1" id="orderSummary" aria-labelledby="offcanvasTopLabel">
				<div class="offcanvas-header border-bottom border-4 border-warning">
					<h5 id="offcanvasTopLabel">Order Summary</h5>
					<button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
				</div>
				<div class="offcanvas-body" id="orderSummaryBody"></div>
			</div>

			<!-- Order delivery details -->
			<div class="offcanvas offcanvas-end" tabindex="-1" id="orderDeliveryDetails" aria-labelledby="offcanvasTopLabel">
				<div class="offcanvas-header border-bottom border-4 border-warning">
					<h5 id="offcanvasTopLabel">Order Delivery Details</h5>
					<button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
				</div>
				<div class="offcanvas-body" id="orderDeliveryDetailsBody"></div>
			</div>

			<!-- Order payment details -->
			<div class="offcanvas offcanvas-end" tabindex="-1" id="orderPaymentDetails" aria-labelledby="offcanvasTopLabel">
				<div class="offcanvas-header border-bottom border-4 border-warning">
					<h5 id="offcanvasTopLabel">Order Payment Details</h5>
					<button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
				</div>
				<div class="offcanvas-body" id="orderPaymentDetailsBody"></div>
			</div>

			<!-- Product Details -->
			<div class="modal fade" id="productDetails" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="productDetailsLabel" aria-hidden="true">
				<div class="modal-dialog modal-lg">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="productDetailsLabel">Product Details</h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div class="modal-body" id="productDetailsBody">
							<div class="text-center">
								<div class="spinner-border text-primary" role="status">
									<span class="visually-hidden">Loading...</span>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
						</div>
					</div>
				</div>
			</div>

			<!-- Product Quantity -->
			<div class="modal" id="addProductQty" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addProductQtyLabel" aria-hidden="true">
				<div class="modal-dialog modal-sm modal-dialog-centered">
					<div class="modal-content">
						<div class="modal-body" id="addProductQtyBody">
							<div class="d-flex justify-content-between">
								<h6 class="modal-title" id="addProductQtyLabel">Select Quantity</h6>
								<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
							</div>
							<table class="table table-borderless table-sm small mt-4 mb-2">
								<tbody>
								<tr>
									<td>
										<select id="addProductQtyModal-quantity"
												name="addProductQtyModal-quantity"
												class="form-select form-select-sm"
										>
											<?php foreach(range(1,100) as $qty): ?>
												<option value="<?= $qty ?>"><?= $qty ?></option>
											<?php endforeach; ?>
										</select>
									</td>
									<td style="width: 50px;">
										<button
												id="addProductQtyModal-saveButton"
												class="btn btn-primary btn-sm ms-2"
												onclick="saveProductQtyToCart()">
											Add
										</button>
									</td>
									<td style="width:45px">
										<div id="addProductQtyModal-spinner" class="d-none">
											<div class="spinner-border spinner-border-sm mt-2 text-primary" role="status">
												<span class="visually-hidden">Loading...</span>
											</div>
										</div>
									</td>
								</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>

			<!-- Alert -->
			<div class="modal" id="alertModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="alertModalLabel" aria-hidden="true">
				<div class="modal-dialog modal-sm modal-dialog-centered">
					<div class="modal-content">
						<div class="modal-body" id="alertModalBody">
							<div class="d-flex justify-content-between">
								<h5 class="modal-title" id="alertModalLabel">Alert!</h5>
								<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
							</div>
							<hr>
							<div class="mt-3 mb-3" id="alertModalContent"></div>
						</div>
					</div>
				</div>
			</div>

			<!-- Ajax loader -->
			<div id="fullLoader">
				<div class="modal" id="fullLoaderBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
					<div class="modal-dialog modal-dialog-centered modal-sm">
						<div class="modal-content">
							<div class="modal-body text-center text-purple">
								<div class="d-flex justify-content-center">
									<div class="spinner-border text-purple" role="status" aria-hidden="true"></div>
									<span class="ms-3 fs-5">Loading...</span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- Confirm Popup -->
			<div class="modal" id="confirmPopup" data-bs-backdrop="static" data-bs-keyboard="false"
				 aria-labelledby="deleteModal" aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="deleteModal"></h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
								<span aria-hidden="true"></span>
							</button>
						</div>
						<div class="modal-body">
							<div class="content">Are you sure?</div>
						</div>
						<div class="modal-footer mt-3 p-1">
							<a href="#" class="actionLink btn btn-danger btn-sm me-2 w-25"><span class="ok">Ok</span></a>
							<button type="button" class="actionLinkButton btn btn-danger btn-sm me-2" data-bs-dismiss="modal"><span
										class="ok">Ok</span></button>
							<button type="button" class="btn btn-outline-secondary btn-sm cancelButton w-25" data-bs-dismiss="modal">
								Cancel
							</button>
						</div>
					</div>
				</div>
			</div>

			<!-- delete product from cart popup -->
			<div class="modal fade" id="deleteProductFromCartPopup" data-bs-backdrop="static" data-bs-keyboard="false"
				 aria-labelledby="deleteProductFromCartModal" aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered">
					<div class="modal-content">
						<div class="modal-body">
							<div class="d-flex justify-content-between">
								<div>
									Are you sure you want to delete this product?
									<div id="deleteProductFromCartPopupProductName" class="fw-bold mt-3"></div>
								</div>
								<div class="ms-2">
									<button
											type="button"
											class="btn-close p-2"
											data-bs-dismiss="modal"
											aria-label="Close"
											onclick="deleteProductFromCartPopup.hide(); myShoppingCart.show();">
										<span aria-hidden="true"></span>
									</button>
								</div>
							</div>
						</div>
						<div class="modal-footer mt-2 p-1">
							<a href="#" class="deleteLink btn btn-danger btn-sm me-3 w-25" onclick="deleteProductFromCart()"><span class="ok">Ok</span></a>
							<button
									type="button"
									class="btn btn-outline-secondary btn-sm w-25"
									data-bs-dismiss="modal"
									onclick="deleteProductFromCartPopup.hide(); myShoppingCart.show();">Cancel</button>
						</div>
					</div>
				</div>
			</div>

			<!-- Toast messages -->
			<div aria-live="polite" aria-atomic="true" class="position-relative">
				<!-- Position it: -->
				<!-- - `.toast-container` for spacing between toasts -->
				<!-- - `.position-absolute`, `top-0` & `end-0` to position the toasts in the upper right corner -->
				<!-- - `.p-3` to prevent the toasts from sticking to the edge of the container  -->
				<div class="toast-container fixed-top end-0 p-3" style="left: auto">
					<div id="ToastMessage" class="d-none">
						<div id="toastDiv" class="toast toast-js text-white border-white border-2" role="alert" aria-live="assertive" aria-atomic="true">
							<div class="d-flex align-items-center justify-content-between">
								<div class="toast-body"></div>
								<button type="button" class="btn-close btn-close-white ml-auto me-2" data-bs-dismiss="toast"
										aria-label="Close"></button>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- Show login popup in place order section -->
			<div class="modal fade" id="placeOrderLoginPopup" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="placeOrderLoginPopupLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-body">
							<div>If you are already registered, please <a href="/users/login" class="text-orange">login</a> to place an Order.</div>

							<div class="mt-3 text-center">(OR)</div>

							<div class="mt-3">
								Click to <a href="#" id="placeOrderLinkGuest" class="text-orange" onclick="placeOrder(1)">Auto Register & Place Order</a>.
								A new account will be created for you and order will be placed based on the contact information provided in the order details.
							</div>

							<div id="confirmOrderSpinnerGuest" class="mt-4"></div>

						</div>
						<div class="modal-footer">
							<a href="/users/login" role="button" class="btn btn-orange">Go to Login Page</a>
							<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="container mt-4">
			<?php echo $this->Session->flash(); ?>
		</div>

		<!-- footer -->
		<footer>
			<nav class="navbar navbar-expand-lg navbar-static border-top <?= $navbarTheme ?>">
				<div class="container-fluid justify-content-center text-center mb-2 small">
					<ul class="navbar-nav ml-auto">
						<li class="nav-item px-1">
							<a class="nav-link px-1" href="/sites/about">About Us</a>
						</li>
						<li class="nav-item px-1">
							<a class="nav-link px-1" href="/sites/contact">Contact Us</a>
						</li>
						<li class="nav-item px-1">
							<a class="nav-link px-1" href="/sites/tos">Terms of Service</a>
						</li>
						<li class="nav-item px-1">
							<a class="nav-link px-1" href="/sites/privacy">Privacy Policy</a>
						</li>
						<li class="nav-item px-1">
							<a class="nav-link px-1" href="/testimonials/">Testimonials</a>
						</li>
					</ul>
				</div>
			</nav>
		</footer>
	</div>




<div class="container">
	<?php echo $this->element('sql_dump'); ?>
</div>

<script src="/vendor/bootstrap-5.1.0-dist/js/bootstrap.bundle.min.js"></script>
<script src="/vendor/jquery-lazy-load/jquery.lazyload.min.js"></script>
<script src="/vendor/lightbox2-2.11.3/dist/js/lightbox.min.js"></script>

<!--
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"
		integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN"
		crossorigin="anonymous"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha3/dist/js/bootstrap.min.js"
		integrity="sha384-t6I8D5dJmMXjCsRLhSzCltuhNZg6P10kE0m0nAncLUjH6GeYLhRU1zfLoW3QNQDF"
		crossorigin="anonymous"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.lazyload/1.9.1/jquery.lazyload.min.js"></script>


<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"
		integrity="sha512-k2GFCTbp9rQU412BStrcD/rlwv1PYec9SNrkbQlo6RZCf75l6KcC3UwDY8H5n5hl4v77IDtIPwOk9Dqjs/mMBQ=="
		crossorigin="anonymous" async></script>
-->
<?= $this->element('customjs') ?>
<script>
	$(document).ready(function() {
		$.fn.modal.Constructor.prototype.enforceFocus = function (){};
	})

</script>

</body>
</html>
