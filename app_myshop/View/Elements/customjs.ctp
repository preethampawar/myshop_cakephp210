<?php
App::uses('Order', 'Model');
?>
<script>
	var handleError = function (err) {
		alert('Network error. Please check the internet connection and try again.')
		return new Response(JSON.stringify({
			code: 400,
			message: 'Network Error'
		}));
	};

	// Implementation of JS async calls to consume APIs

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
		}).catch(handleError);

		if (response.ok) {
			return response.json();
		} else {
			return Promise.reject(response);
		}
	}

	// GET page implementation:
	async function getPage(url = '', data = {}) {
		const response = await fetch(url + '?isAjax=1').catch(handleError);

		if (response.ok) {
			return response.text(); // parses into html
		} else {
			return Promise.reject(response);
		}
	}

	// GET data implementation:
	async function getData(url = '', data = {}) {
		const response = await fetch(url).catch(handleError);

		if (response.ok) {
			return response.json();
		} else {
			return Promise.reject(response);
		}
	}

	function handleErrors(response) {
		if (!response.ok) {
			throw Error(response.statusText);
		}
		return response;
	}
</script>

<script>
	// page reload
	function refreshPage() {
		location.reload();
	}

	// lazy load images
	function lazyLoadImages() {
		if ($("img.lazy").length) {
			$("img.lazy").lazyload({
				effect: "fadeIn"
			});
		}
	}

	// init bootstrap tooltips
	function initTooltips() {
		var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
		var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
			return new bootstrap.Tooltip(tooltipTriggerEl)
		});
	}

	// show toast messages
	function showToastMessages() {
		var toastElList = [].slice.call(document.querySelectorAll('.toast-js'))
		var toastList = toastElList.map(function (toastEl) {
			return new bootstrap.Toast(toastEl)
		});

		toastList.forEach(toast => toast.show());
	}

	// show server toast messages
	function showServerToastMessages() {
		var toastElList = [].slice.call(document.querySelectorAll('.toast-php'))
		var toastList = toastElList.map(function (toastEl) {
			return new bootstrap.Toast(toastEl)
		});

		toastList.forEach(toast => toast.show());
	}

	// hide toast messages
	function hideToastMessages() {
		var toastElList = [].slice.call(document.querySelectorAll('.toast'))
		var toastList = toastElList.map(function (toastEl) {
			return new bootstrap.Toast(toastEl)
		});

		toastList.forEach(toast => toast.hide());
	}

	// load shopping cart in top nav
	function loadShoppingCartHeader() {
		let topNavCartHeaderUrl = '/shopping_carts/loadTopNavCartHeader';
		const data = getPage(topNavCartHeaderUrl);
		data.then(function (response) {
			$("#topNavShoppingCart").html(response);
		});

		return data;
	}

	// load shopping cart in top nav
	var cartInfo = null;

	function loadShoppingCart() {
		let topNavCartUrl = '/shopping_carts/loadTopNavCart';
		const data = getPage(topNavCartUrl);

		data.then(function (response) {
			$("#myShoppingCartBody").html(response);
		})

		return data;
	}

	// load shopping cart in top nav
	var cartInfo = null;

	function loadShoppingCart_toberemoved(showCart) {
		showCart = showCart ?? true;

		if (!cartInfo) {
			showFullLoader()
		} else if (showCart) {
			myShoppingCart.show()
		}

		let topNavCartUrl = '/shopping_carts/loadTopNavCart';
		const data = getPage(topNavCartUrl);

		data.then(function (response) {
			$("#myShoppingCartBody").html(response);

			if (!cartInfo) {
				hideFullLoader()

				if (showCart) {
					myShoppingCart.show()
				}
			}

			cartInfo = response;
		})
	}

	var fullLoaderBackdrop = new bootstrap.Modal(document.getElementById('fullLoaderBackdrop'), {
		keyboard: false
	})

	function showFullLoader() {
		fullLoaderBackdrop.show()
	}

	function hideFullLoader() {
		if (!fullLoaderBackdrop) {
			fullLoaderBackdrop = new bootstrap.Modal(document.getElementById('fullLoaderBackdrop'), {
				keyboard: false
			})
		}

		fullLoaderBackdrop.hide()
	}

	// enable light box for images
	function enableLightBoxForImages() {
		if ($('#photogallery').length) {
			$('#photogallery').attr('class', 'active');
		}

	}

	// price formatter
	function formatPrice($value) {
		return '&#8377;'.$value;
	}

	// generate random number
	function getRndInteger(min, max) {
		return Math.floor(Math.random() * (max - min + 1)) + min;
	}

	// show confirm popup
	function showConfirmPopup(url, title = '', content = '', okText = '') {
		let confirmPopup;

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

	// show delete product from cart popup
	var deleteProductFromCartPopup = null;
	function showDeleteProductFromCartPopup(shoppingCartProductId, productName) {
		if (!deleteProductFromCartPopup) {
			deleteProductFromCartPopup = new bootstrap.Modal(document.getElementById('deleteProductFromCartPopup'), {
				keyboard: false
			});
		}
		myShoppingCart.hide()

		deleteProductFromCartPopup.show();

		$('#deleteProductFromCartPopupProductName').html(productName);
		$('#deleteProductFromCartPopupProductName').data('delete-shopping-cart-product-id', shoppingCartProductId);
	}

	function deleteProductFromCart() {
		const shoppingCartProductId = $('#deleteProductFromCartPopupProductName').data('delete-shopping-cart-product-id')
		const url = '/ShoppingCarts/deleteShoppingCartProduct/'+shoppingCartProductId+'/1'

		const result = getData(url)

		result.then( function (response) {
			if (response.error) {
				alert('error');
				return;
			}

			loadShoppingCart()
			loadShoppingCartHeader()
		})
		result.finally( function() {
			deleteProductFromCartPopup.hide();
			myShoppingCart.show()
		})

		return result
	}

	let spinner = `<div class="text-center">
						<div class="spinner-border text-primary" role="status">
							<span class="visually-hidden">Loading...</span>
						</div>
					</div>`;
	let productDetailsModal = null;

	function showProductDetails(categoryId, productId) {

		if (!productDetailsModal) {
			productDetailsModal = new bootstrap.Modal(document.getElementById('productDetails'), {
				keyboard: false
			});
		}

		productDetailsModal.show();
		$("#productDetailsBody").html(spinner);

		let productDetailsUrl = '/products/getDetails/' + categoryId + '/' + productId;
		const data = getPage(productDetailsUrl);
		data.then(function (response) {
			$("#productDetailsBody").html(response);
		});
	}

	let addProductQtyModal = null;

	function showAddProductQtyModal(categoryId, productId) {

		if (!addProductQtyModal) {
			addProductQtyModal = new bootstrap.Modal(document.getElementById('addProductQty'), {
				keyboard: false
			});
		}

		addProductQtyModal.show();

		$('#addProductQtyModal-quantity').val(1);
		$('#addProductQtyModal-quantity').data('category-id', categoryId);
		$('#addProductQtyModal-quantity').data('product-id', productId);

		console.log($('#addProductQtyModal-quantity').data('category-id'))
		//
		// $('#addProductQtyModal-saveButton').click(function () {
		// 	let productQty = $('#addProductQtyModal-quantity').val();
		// 	console.log(productQty)
		// })

		// let productDetailsUrl = '/products/getDetails/' + categoryId + '/' + productId;
		// const data = getPage(productDetailsUrl);
		// data.then(function (response) {
		// 	$("#productDetailsBody").html(response);
		// });
	}

	function addToCartFromProductDetailsPage(categoryId, productId, qty) {
		qty = !qty ? 1 : qty;

		$('#addQtyProductDetails-spinner').removeClass('d-none');

		const response = addToCart(categoryId, productId, qty, null);

		response.then(function (data) {
			$('#ToastMessage').removeClass('d-none');
			$('#toastDiv').removeClass('bg-primary');
			$('#toastDiv').removeClass('bg-danger');
			$('#toastDiv').removeClass('bg-notice');

			if (data.success == 1) {
				let cartData = loadShoppingCart();
				cartData.finally(function () {
					$('#addQtyProductDetails-spinner').addClass('d-none');
				})

				loadShoppingCartHeader();

				$('#toastDiv').addClass('bg-primary');
				$("#toastDiv .toast-body").html("<i class='fa fa-check-circle'></i> Product successfully added to cart.");
			} else {
				$('#toastDiv').addClass('bg-danger');
				$("#toastDiv .toast-body").html("<i class='fa fa-exclamation-circle'></i> " + data.errorMessage);
			}

			showToastMessages();
		})
		response.finally(function() {
			$('#addQtyProductDetails-spinner').addClass('d-none');
			productDetailsModal.hide()
		})

		return response;

	}

	function saveProductQtyToCart() {
		const productQty = $('#addProductQtyModal-quantity').val();
		const categoryId = $('#addProductQtyModal-quantity').data('category-id');
		const productId = $('#addProductQtyModal-quantity').data('product-id');

		$('#addProductQtyModal-spinner').removeClass('d-none');

		const response = addToCart(categoryId, productId, productQty, null);

		response.then(function (data) {

			$('#ToastMessage').removeClass('d-none');
			$('#toastDiv').removeClass('bg-primary');
			$('#toastDiv').removeClass('bg-danger');
			$('#toastDiv').removeClass('bg-notice');

			if (data.success == 1) {
				let cartData = loadShoppingCart();
				cartData.finally(function () {
					$('#addProductQtyModal-spinner').addClass('d-none');
				})

				loadShoppingCartHeader();

				$('#toastDiv').addClass('bg-primary');
				$("#toastDiv .toast-body").html("<i class='fa fa-check-circle'></i> Product successfully added to cart.");
			} else {
				$('#addProductQtyModal-spinner').addClass('d-none');
				$('#toastDiv').addClass('bg-danger');
				$("#toastDiv .toast-body").html("<i class='fa fa-exclamation-circle'></i> " + data.errorMessage);
			}

			showToastMessages();
		})
		response.finally(function() {
			addProductQtyModal.hide()
		})

		return response;
	}

	function showProductDetails_tobedeleted(categoryId, productId) {
		let myModal = new bootstrap.Modal(document.getElementById('productModal' + productId), {
			keyboard: false
		});
		myModal.show();

		let productDetailsUrl = '/products/getDetails/' + categoryId + '/' + productId;
		const data = getPage(productDetailsUrl);
		data.then(function (response) {
			$("#productModal" + productId + " .modal-body").html(response);
		});
	}

	// show ShoppingCart content
	let myShoppingCartElement = document.getElementById('myShoppingCart')
	var myShoppingCart = new bootstrap.Offcanvas(myShoppingCartElement)

	function showMyShoppingCart() {
		myShoppingCart.show()

		loadShoppingCart();
	}

	// show Order Summary
	let orderSummaryElement = document.getElementById('orderSummary')
	var orderSummary = new bootstrap.Offcanvas(orderSummaryElement)

	function showOrderSummary() {
		loadOrderSummary()
		orderPaymentDetails.hide()
		orderSummary.show()
	}

	// show Order Delivery Details
	let orderDeliveryDetailsElement = document.getElementById('orderDeliveryDetails')
	var orderDeliveryDetails = new bootstrap.Offcanvas(orderDeliveryDetailsElement)

	function showOrderDeliveryDetails() {
		loadOrderDeliveryDetails()
		myShoppingCart.hide()
		orderDeliveryDetails.show()
	}

	// show Order Payment Details
	let orderPaymentDetailsElement = document.getElementById('orderPaymentDetails')
	var orderPaymentDetails = new bootstrap.Offcanvas(orderPaymentDetailsElement)

	function showOrderPaymentDetails() {
		loadOrderPaymentDetails()
		orderDeliveryDetails.hide()
		orderPaymentDetails.show()
	}

	// load order Summary
	function loadOrderSummary() {
		let orderSummaryUrl = '/shopping_carts/loadOrderSummary';
		const data = getPage(orderSummaryUrl);
		$("#orderSummaryBody").html(spinner);
		data.then(function (response) {
			$("#orderSummaryBody").html(response);
		})

		return data;
	}

	// load Order Delivery Details
	function loadOrderDeliveryDetails() {
		let loadDeliveryDetailsUrl = '/shopping_carts/loadOrderDeliveryDetails';
		const data = getPage(loadDeliveryDetailsUrl);
		$("#orderDeliveryDetailsBody").html(spinner);
		data.then(function (response) {
			$("#orderDeliveryDetailsBody").html(response);
		})

		return data;
	}

	// load Order Payment Details
	function loadOrderPaymentDetails() {
		let loadPaymentDetailsUrl = '/orders/loadOrderPaymentDetails';
		const data = getPage(loadPaymentDetailsUrl);
		$("#orderPaymentDetailsBody").html(spinner);
		data.then(function (response) {
			$("#orderPaymentDetailsBody").html(response);
		})

		return data;
	}

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

	function saveOrderDeliveryDetails() {
		const saveOrderDeliveryDetails = '/orders/saveOrderDeliveryDetails'
		let form = document.getElementById('ShoppingCartLoadOrderDeliveryDetailsForm')
		const formValid = form.checkValidity()
		form.classList.add('was-validated')

		$('#deliveryErrorAlert').addClass('d-none')

		if(formValid) {
			let formData = new FormData(document.getElementById('ShoppingCartLoadOrderDeliveryDetailsForm'));
			let customerName = formData.get('data[customer_name]')
			let customerPhone = formData.get('data[customer_phone]')
			let customerEmail = formData.get('data[customer_email]')
			let customerAddress = formData.get('data[customer_address]')
			let customerMessage = formData.get('data[customer_message]')
			let data = {
				'customerName': customerName,
				'customerPhone': customerPhone,
				'customerEmail': customerEmail,
				'customerAddress': customerAddress,
				'customerMessage': customerMessage,
			}

			const response = postData(saveOrderDeliveryDetails, data)

			let loader = spinner+'<div class="text-center small">Please wait.</div>'

			$('#orderDeliveryDetailsSpinner').html(loader);
			$('#saveOrderDeliveryDetailsButton').addClass('disabled');

			response.then(function (data) {
				if (data.error != 1) {
					showOrderPaymentDetails();
				} else {
					$('#deliveryErrorAlert').removeClass('d-none')
					$('#deliveryErrorAlert .content').html(data.errorMsg)
					$('#deliveryErrorAlert .btn-close').focus()
				}
			}).finally(function() {
				$('#orderDeliveryDetailsSpinner').html('');
				$('#saveOrderDeliveryDetailsButton').removeClass('disabled');
			})

		} else {
			$('#deliveryErrorAlert .content').html('There are some missing/invalid values in the form. Please fix those and try again.')
			$('#deliveryErrorAlert').removeClass('d-none')
			$('#deliveryErrorAlert .btn-close').focus()
		}
	}


	function saveOrderPaymentDetails() {
		const saveOrderPaymentUrl = '/orders/saveOrderPaymentDetails'
		let form = document.getElementById('ShoppingCartLoadOrderPaymentDetailsForm')
		const formValid = form.checkValidity()
		form.classList.add('was-validated')

		$('#paymentErrorAlert').addClass('d-none')

		if(formValid) {
			let formData = new FormData(document.getElementById('ShoppingCartLoadOrderPaymentDetailsForm'));
			let paymentMethod = formData.get('data[payment_method]')
			let paymentReferenceNo = formData.get('data[payment_reference_no]')
			let data = {
				'paymentMethod': paymentMethod,
				'paymentReferenceNo': paymentReferenceNo,
			}

			const response = postData(saveOrderPaymentUrl, data)

			let loader = spinner+'<div class="text-center small">Please wait.'

			$('#orderPaymentDetailsSpinner').html(loader);
			$('#placeOrderButton').addClass('disabled');

			response.then(function (data) {
				if (data.error != 1) {
					showOrderSummary()
				} else {
					$('#paymentErrorAlert').removeClass('d-none')
					$('#paymentErrorAlert .content').html(data.errorMsg)
					$('#paymentErrorAlert .btn-close').focus()
				}
			}).finally(function() {
				$('#orderPaymentDetailsSpinner').html('');
				$('#placeOrderButton').removeClass('disabled');
			})

		} else {
			$('#paymentErrorAlert .content').html('There are some missing/invalid values in the form. Please fix those and try again.')
			$('#paymentErrorAlert').removeClass('d-none')
			$('#paymentErrorAlert .btn-close').focus()
		}
	}


	function placeOrder() {
		const placeOrderUrl = '/orders/create'

		let data = {
			'confirmed': 1,
		}

		const response = postData(placeOrderUrl, data)

		let loader = spinner+'<div class="text-center small">Please wait.<br>Your order is in process. Do not press back button.</div>'

		$('#confirmOrderSpinner').html(loader);
		$('#placeOrderButton').addClass('disabled');

		response.then(function (data) {
			if (data.error != 1) {
				loadShoppingCart();
				loadShoppingCartHeader();
				orderSummary.hide()
				showAlert(data.successMsg, 'Success!')
			} else {
				alert(data.errorMsg)
			}
		}).finally(function() {
			$('#confirmOrderSpinner').html('');
			$('#placeOrderButton').removeClass('disabled');
		})
	}

	var alertModal = new bootstrap.Modal(document.getElementById('alertModal'), {
		keyboard: false
	})

	function showAlert(msg, title) {
		title = title ? title : 'Alert!'

		$('#alertModalLabel').html(title)
		$('#alertModalContent').html(msg)
		alertModal.show()
	}

	function hideAlert() {
		alertModal.hide()
	}

	// add product to cart
	function addToCart(categoryId, productId, quantity, shoppingCartId) {
		const addToCartUrl = '/shopping_carts/addToCart';
		let qty = parseInt(quantity)

		if (isNaN(qty)) {
			qty = 1
		}

		qty = qty < 1 ? 1 : qty

		if (!shoppingCartId) {
			shoppingCartId = null
		}

		let data = {
			'ShoppingCartProduct': {
				'quantity': qty,
				'categoryId': categoryId,
				'productId': productId,
				'shoppingCartId': shoppingCartId,
			}
		}

		const response = postData(addToCartUrl, data);

		//
		// response.then(function (data) {
		// 	if (data.success == 1) {
		// 		loadShoppingCart();
		// 		loadShoppingCartHeader();
		// 	}
		// })

		return response
	}

	function updateProductQtyFromShoppingCart(categoryId, productId, quantity, shoppingCartId) {
		let qty = parseInt(quantity);

		if (isNaN(qty)) {
			qty = 1
		}

		qty = qty < 1 ? 1 : qty;

		$('#updatingCartSpinner' + shoppingCartId).removeClass('d-none');
		const response = addToCart(categoryId, productId, qty, shoppingCartId);

		response.then(function (data) {
			if (data.success == 1) {
				let cartData = loadShoppingCart();

				cartData.finally(function () {
					$('#updatingCartSpinner' + shoppingCartId).addClass('d-none');
				})

				loadShoppingCartHeader();
			} else {
				$('#updatingCartSpinner' + shoppingCartId).addClass('d-none');
			}
		})

		return response;
	}

	// add product to cart
	function addToCart_toberemoved(categoryId, productId, quantity, shoppingCartId, showCart) {

		showCart = showCart ?? true;

		if (!shoppingCartId) {
			shoppingCartId = null
		}

		const addToCartUrl = '/shopping_carts/addToCart';
		let data = {
			'ShoppingCartProduct': {
				'quantity': quantity,
				'categoryId': categoryId,
				'productId': productId,
				'shoppingCartId': shoppingCartId,
			}
		}

		if (shoppingCartId) {
			$('#updatingCartSpinner' + shoppingCartId).removeClass('d-none');
		}

		const response = postData(addToCartUrl, data);

		response.then(function (data) {
			if (data.success == 1) {
				loadShoppingCart(showCart);
				loadShoppingCartHeader();
			}
		}).finally(function () {
			if (shoppingCartId) {
				$('#updatingCartSpinner' + shoppingCartId).addClass('d-none');
			}
		})

		return response
	}
</script>

<script>
	// scripts executed after the page load
	$(document).ready(function () {

		<?php if ($this->Session->read('Site.shopping_cart')): ?>
		try {
			loadShoppingCartHeader();
			loadShoppingCart()
		} catch (err) {
			console.log('Error - Shopping cart top nav header: ', err.message);
		}
		<?php endif; ?>

		try {
			lazyLoadImages();
		} catch (err) {
			console.log('Error - Lazy load images: ', err.message);
		}

		try {
			showServerToastMessages();
		} catch (err) {
			console.log('Error - Toast messages: ', err.message);
		}

		try {
			enableLightBoxForImages()
		} catch (err) {
			console.log('Error - Light box for images: ', err.message);
		}

		try {
			initTooltips();
		} catch (err) {
			console.log('Error - Bootstrap tooltips: ', err.message);
		}
	});
</script>

