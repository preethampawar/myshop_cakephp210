<!doctype html>
<html lang="en">
<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?= $this->Session->read('Site.title') ?></title>

	<!-- Bootstrap CSS -->
	<!-- todo: delete it
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
		  integrity="sha384-CuOF+2SnTUfTwSZjCXf01h7uYhfOBuxIhGKPbfEJ3+FqH/s6cIFN9bGr1HmAg4fQ" crossorigin="anonymous">
		  -->

	<!-- light box css -->
	<!-- todo: delete it
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css"
		  integrity="sha512-ZKX+BvQihRJPA8CROKBhDNvoc2aDMOdAlcm7TUQY+35XYtrd3yh95QOOhsPDQY9QnKE0Wqag9y38OIgEvb88cA=="
		  crossorigin="anonymous"/>
		  -->


	<link rel="stylesheet" href="/vendor/bootstrap-5.0.0-dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="/vendor/lightbox2-2.11.3/dist/css/lightbox.min.css">
	<link rel="stylesheet" href="/vendor/fontawesome-free-5.15.3-web/css/all.min.css">
	<link rel="stylesheet" href="/css/site.css">
	<?= $this->element('customcss') ?>

	<script src="/vendor/jquery/jquery-3.6.0.min.js"></script>
	<!-- todo: delete it
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	-->

	<?php
	if ((isset($loadVueJs) && $loadVueJs == true) || $this->Session->read('Site.shopping_cart') == true) {
		?>
		<script src="/vendor/vue/vue.min.js"></script>


		<!-- development version, includes helpful console warnings -->
		<!--		<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>-->

		<!-- production version, optimized for size and speed -->
<!--		<script src="/vendor/vue/vuejs2.6.12.js"></script> dev version-->

		<!-- todo: delete it
		<script src="https://cdn.jsdelivr.net/npm/vue"></script>
		-->
		<?php
	}
	?>

	<!-- todo: delete it
	<script src="https://kit.fontawesome.com/231b614f56.js" crossorigin="anonymous" async></script>
	-->
</head>

<body>

<nav class="navbar navbar-dark bg-dark bg-gradient">
	<div class="container">
		<a class="navbar-brand text-truncate" href="#"><?= $this->Session->read('Site.title') ?></a>
	</div>
</nav>

<nav class="navbar p-0" role="navigation">
	<!-- navbar-side will go here -->
	<ul class="navbar-side navbar-nav bg-white text-dark px-2 text-left list-group" id="navbarSide">
		<?php echo $this->element('categories_menu'); ?>
	</ul>
	<div class="overlay"></div>
</nav>

<nav class="navbar navbar-expand-lg navbar-dark navbar-static bg-purple bg-gradient">
	<div class="container">
		<div class="navbar-toggler border-0 p-1 py-0 text-white" type="button" data-bs-toggle="collapse"
			 data-bs-target="#navbarNav"
			 aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
			<i class="fa fa-ellipsis-v"></i> Menu
		</div>
		<a class="navbar-brand" href="/">
			<i class="fa fa-home"></i>
		</a>
		<div class="collapse navbar-collapse" id="navbarNav">
			<ul class="navbar-nav ml-auto">
				<?php
				if ($this->App->isSellerForThisSite()) {
					?>
					<li class="nav-item px-1">
						<a class="nav-link px-1" href="/users/setView/seller">Manage
							Store</a>
					</li>
					<!--
					<li class="nav-item">
						<a class="nav-link" href="/users/myaccount">My Account</a>
					</li>
					-->
					<li class="nav-item px-1">
						<a class="nav-link px-1" href="/sites/contact">Contact</a>
					</li>
					<li class="nav-item px-1">
						<a class="nav-link px-1" href="/sites/paymentInfo">Payment Details</a>
					</li>
					<li class="nav-item px-1">
						<a class="nav-link px-1" href="/users/logout">Logout</a>
					</li>
					<?php
				} else { ?>

					<?php if ($this->Session->read('Site.shopping_cart')): ?>
					<li class="nav-item px-1">
						<a class="nav-link fw-normal px-1" href="#">Orders</a>
					</li>
					<?php endif; ?>

					<li class="nav-item px-1">
						<a class="nav-link px-1" href="/sites/contact">Contact</a>
					</li>
					<li class="nav-item px-1">
						<a class="nav-link px-1" href="/sites/paymentInfo">Payment Details</a>
					</li>
					<li class="nav-item px-1">
						<?php if ($this->Session->check('User.id')): ?>
							<a class="nav-link px-1" href="/users/logout">Logout</a>
						<?php else: ?>
							<a class="nav-link px-1" href="/users/login">Login</a>
						<?php endif; ?>
					</li>
				<?php } ?>
			</ul>
		</div>
	</div>
</nav>

<div class="shadow-sm border-bottom bg-white">
	<ul class="nav container justify-content-between py-2">
		<li class="nav-item">
			<div id="topCategoriesMenu">
				<a href="#" class="nav-link  fw-normal" data-bs-toggle="offcanvas" data-bs-target="#categoriesMenu">
					<i class="fa fa-bars"></i> Shop By Category
				</a>
			</div>
		</li>
		<?php if ($this->Session->read('Site.shopping_cart')): ?>
			<li class="nav-item">
				<div id="topNavShoppingCart">
					<a href="#" class="nav-link  fw-normal" data-bs-toggle="offcanvas" data-bs-target="#myShoppingCart">
						<i class="fa fa-shopping-cart"></i> My Cart <span class="badge rounded-pill bg-orange">0</span>
					</a>
				</div>
			</li>
		<?php endif; ?>
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
	<?php if ($this->Session->read('Site.shopping_cart')): ?>
<!--		<div id="topNavShoppingCart"></div>-->
	<?php endif; ?>

	<?php echo $this->fetch('content'); ?>

	<?php
	$showPaymentContactInfo = false;

	if ($this->request->params['controller'] != 'users'
		&& $this->request->params['controller'] != 'sites') {
		$showPaymentContactInfo = true;
	}

	if ($showPaymentContactInfo && !empty($this->Session->read('Site.contact_info'))):
		?>
		<div class="text-center small alert alert-info">
			<h4 class="mb-3 text-decoration-underline">Contact</h4>
			<?= $this->Session->read('Site.contact_info') ?>
		</div>
	<?php
	endif;
	?>

	<?php
	if ($showPaymentContactInfo && !empty($this->Session->read('Site.payment_info'))):
		?>

		<div class="text-center small alert alert-info">
			<h4 class="mb-3 text-decoration-underline">Payment Details</h4>
			<?= $this->Session->read('Site.payment_info') ?>
		</div>
	<?php
	endif;
	?>

	<?php
	if ($showPaymentContactInfo && !empty($this->Session->read('Site.tos'))):
		?>
		<div class="text-center small alert alert-warning">
			Please read our <a href="/sites/tos">Terms of Service</a> before you place an order with us.
		</div>
	<?php
	endif;
	?>

	<br>

	<div id="ToastMessage" class="fixed-top d-none" style="width:16rem; left: auto; margin-top: 0.5rem; margin-right: 0.5rem;">
		<div id="toastDiv" class="toast text-white border-white border-2" role="alert" aria-live="assertive" aria-atomic="true">
			<div class="d-flex align-items-center">
				<div class="toast-body"></div>
				<button type="button" class="btn-close btn-close-white ml-auto me-2" data-bs-dismiss="toast"
						aria-label="Close"></button>
			</div>
		</div>
	</div>


	<div class="offcanvas offcanvas-end" tabindex="-1" id="myShoppingCart" aria-labelledby="offcanvasTopLabel">
		<div class="offcanvas-header border-bottom border-4 border-warning">
			<h5 id="offcanvasTopLabel">My Shopping Cart</h5>
			<button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
		</div>
		<div class="offcanvas-body" id="myShoppingCartBody"></div>
	</div>


	<div class="offcanvas offcanvas-start" tabindex="-1" id="categoriesMenu" aria-labelledby="offcanvasTopLabel">
		<div class="offcanvas-header border-bottom border-4 border-warning">
			<h5 id="offcanvasTopLabel">Shop By Category</h5>
			<button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
		</div>
		<div class="offcanvas-body" id="categoriesMenuBody">
			<?php echo $this->element('categories_menu'); ?>
		</div>
	</div>


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
</div>

<div class="container">
	<?php echo $this->element('sql_dump'); ?>
</div>


<script src="/vendor/bootstrap-5.0.0-dist/js/bootstrap.bundle.min.js"></script>
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

</body>
</html>
