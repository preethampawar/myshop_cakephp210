<?php
App::uses('ShoppingCart', 'Model');
$shoppingCartModel = new ShoppingCart;
$shoppingCart = $shoppingCartModel->getShoppingCartProducts($this->Session->read('ShoppingCart.id'));

$selectBoxQuantityOptions = '';
for ($i = 1; $i <= 50; $i++) {
	$selectBoxQuantityOptions .= "<option value='$i'>$i</option>";
}

$totalItems = 0;
if (isset($shoppingCart['ShoppingCartProduct']) and !empty($shoppingCart['ShoppingCartProduct'])) {
	foreach ($shoppingCart['ShoppingCartProduct'] as $row) {
		$totalItems += $row['quantity'];
	}
	?>

	<a href="#" class="nav-link  fw-normal" data-bs-toggle="offcanvas" data-bs-target="#myShoppingCart">
		<i class="fa fa-shopping-cart"></i> My Cart <span class="badge rounded-pill bg-orange"><?php echo $totalItems; ?></span>
	</a>

	<div class="mb-4 shadow py-1 px-2 border-4 border-top border-warning d-none">
		<nav class="navbar">
			<div class="container-fluid py-1 text-small">
				<div class="px-0 py-0 text-dark border-0" role="button" data-bs-toggle="offcanvas"
					 data-bs-target="#myShoppingCart">
					<span class="fa fa-cart-arrow-down"></span>
					<span class="fw-bold small">My Cart</span>
					<span class="fa fa-caret-down"></span>
				</div>
				<div>
					<b><?php echo $totalItems; ?></b>
					item(s) in <a href="#myShoppingCart"
								  data-bs-toggle="offcanvas"
								  data-bs-target="#myShoppingCart">cart</a>.
				</div>
			</div>
		</nav>
	</div>
	<?php
} else {
	?>
	<a href="#" class="nav-link  fw-normal" data-bs-toggle="offcanvas" data-bs-target="#myShoppingCart">
		<i class="fa fa-shopping-cart"></i> My Cart <span class="badge rounded-pill bg-orange">0</span>
	</a>
	<?php
}
?>

