<?php
$homepageActive = (bool)($homepage ?? false);
$featuredPageActive = (bool)($featuredPage ?? false);

if ($homepageActive === false && $featuredPageActive === false) {
	$homepageActive = true;
}
$activeAlertClass = "alert-danger";
$inactiveAlertClass = "alert-info"
?>

<header>
	<ul class="nav nav-tabs d-none">
		<li class="nav-item">
			<a class="nav-link <?= $homepageActive ? ' fw-bold active' : '' ?>" aria-current="page" href="/products/showFeatured"><span class="text-orange"><i class="fa fa-fire"></i></span> Hot Deals</a>
		</li>
		<li class="nav-item">
			<a class="nav-link <?= $featuredPageActive ? ' fw-bold active' : '' ?>" href="/products/showAll">Show All Products</a>
		</li>
	</ul>
</header>


<div class="table-responsive mt-3">
	<div class="hstack gap-3">		
		<div class="alert alert-danger p-1 mb-2 shadow-sm" role="button">
			<a class="nav-link text-nowrap" aria-current="page" href="/products/showFeatured">
				<i class="fa fa-fire text-orange"></i> Hot Deals
			</a>
		</div>
		<div class="alert alert-primary p-1 mb-2 shadow-sm">
			<a class="nav-link text-nowrap" href="/products/showAll"><i class="fa-solid fa-boxes-stacked text-orange"></i> Show All Products</a>
		</div>
	</div>
</div>
