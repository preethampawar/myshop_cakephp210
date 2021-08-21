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
		<span class="fs-5"><i class="fa fa-shopping-cart"></i></span> My Cart <span class="badge rounded-pill bg-orange"><?php echo $totalItems; ?></span>
	</a>
	<?php
} else {
	?>
	<a href="#" class="nav-link  fw-normal" data-bs-toggle="offcanvas" data-bs-target="#myShoppingCart">
		<span class="fs-5"><i class="fa fa-shopping-cart"></i></span> My Cart <span class="badge rounded-pill bg-orange">0</span>
	</a>
	<?php
}
?>

