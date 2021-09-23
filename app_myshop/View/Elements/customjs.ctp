<?php
// this element should contain only php dependent scripts
App::uses('Order', 'Model');

$linkedLocations = Configure::read('LinkedLocations');
$subdomain = $this->request->subdomains()[0];
$showLocationPopup = false;
if (isset($linkedLocations[$subdomain]) && !empty($linkedLocations[$subdomain])) {
	$showLocationPopup = true;
}
?>
<script defer>
	// check payment method
	function checkPaymentMethod(element) {
		let paymentMethod = element.value

		if (paymentMethod == '<?= Order::PAYMENT_METHOD_COD ?>') {
			$('#paymentReferenceNoDiv').addClass('disabledElement')
			$('#paymentReferenceNo').removeAttr('required')
			$('#paymentReferenceNo').val('')
		} else {
			$('#paymentReferenceNoDiv').removeClass('disabledElement')
			$('#paymentReferenceNo').attr('required', true)
		}
	}

	$(document).ready(function () {
		<?php
		if($showLocationPopup) {
		?>
		selectLocation();

		if (localStorage.getItem('location')) {
			$('#locationTitleSpan').text(localStorage.getItem('location'))
		}
		<?php
		}
		?>

		<?php if ($this->Session->read('Site.shopping_cart')): ?>
		try {
			loadShoppingCartHeader();
			loadShoppingCart()
		} catch (err) {
			console.log('Error - Shopping cart top nav header: ', err.message);
		}
		<?php endif; ?>

		<?php
		/*
	// show slideshow only in homepage
	$slideshowEnabled = (int)$this->Session->read('Site.show_banners') === 1;

	if($slideshowEnabled && $this->request->params['action'] === 'display' && $this->request->params['pass'][0] === 'home') {
	?>
		try {
			showSlideShowInHomePage();
		} catch (err) {
			console.log('Error - Slide show: ', err.message);
		}
	<?php
	}

	*/
		?>
	});
</script>

