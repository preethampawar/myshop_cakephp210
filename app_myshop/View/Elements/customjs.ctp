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
		var toastElList = [].slice.call(document.querySelectorAll('.toast'))
		var toastList = toastElList.map(function (toastEl) {
			return new bootstrap.Toast(toastEl)
		});

		toastList.forEach(toast => toast.show());
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
			bsMyShoppingCart.show()
		}

		let topNavCartUrl = '/shopping_carts/loadTopNavCart';
		const data = getPage(topNavCartUrl);

		data.then(function (response) {
			$("#myShoppingCartBody").html(response);

			if (!cartInfo) {
				hideFullLoader()

				if (showCart) {
					bsMyShoppingCart.show()
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

	function showProductDetails(categoryId, productId) {
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
	var myShoppingCart = document.getElementById('myShoppingCart')
	var bsMyShoppingCart = new bootstrap.Offcanvas(myShoppingCart)

	function showMyShoppingCart()
	{
		bsMyShoppingCart.show()

		loadShoppingCart();
	}

	// add product to cart
	function addToCart(categoryId, productId, quantity, shoppingCartId)
	{
		const addToCartUrl = '/shopping_carts/addToCart';

		if (!shoppingCartId) {
			shoppingCartId = null
		}

		let data = {
			'ShoppingCartProduct': {
				'quantity': quantity,
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

	function updateProductQtyFromShoppingCart(categoryId, productId, quantity, shoppingCartId)
	{
		$('#updatingCartSpinner' + shoppingCartId).removeClass('d-none');
		const response = addToCart(categoryId, productId, quantity, shoppingCartId);

		response.then(function (data) {
			if (data.success == 1) {
				let cartData = loadShoppingCart();

				cartData.finally(function() {
					$('#updatingCartSpinner' + shoppingCartId).addClass('d-none');
				})

				loadShoppingCartHeader();
			} else {
				$('#updatingCartSpinner' + shoppingCartId).addClass('d-none');
			}
		})

		return response;
	}


	function addProductToCart(categoryId, productId, quantity, shoppingCartId)
	{
		$('#updatingCartSpinner' + shoppingCartId).removeClass('d-none');
		const response = addToCart(categoryId, productId, quantity, shoppingCartId);

		response.then(function (data) {
			if (data.success == 1) {
				let cartData = loadShoppingCart();

				cartData.finally(function() {
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
			showToastMessages();
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

