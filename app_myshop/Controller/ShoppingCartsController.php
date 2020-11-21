<?php
App::uses('CakeEmail', 'Network/Email');

class ShoppingCartsController extends AppController
{

	public function beforeFilter()
	{
		parent::beforeFilter();

		// Allow only if shopping(request price quote) is enabled on site
//		if (!$this->Session->read('Site.request_price_quote')) {
//			$this->Session->setFlash('Shopping on this site has been disabled.', 'default', array('class' => 'notice'));
//			$this->redirect($this->request->referer());
//		}

		//$this->Auth->allow('index', 'add', 'edit', 'delete', 'getCartProducts', 'deleteShoppingCartProduct', 'requestQuoteForProduct');
	}

	function add($categoryID, $productID)
	{
		$errorMsg = null;

		if (!$categoryInfo = $this->isSiteCategory($categoryID)) {
			$this->errorMsg('Category Not Found');
		} elseif (!$productInfo = $this->isSiteProduct($productID)) {
			$this->errorMsg('Product Not Found');
		} elseif ($this->request->isPost()) {
			$data = $this->request->data;
			$shoppingCartID = $this->getShoppingCartID();

			if ($shoppingCartID) {
				$tmp['ShoppingCartProduct']['shopping_cart_id'] = $shoppingCartID;
				$tmp['ShoppingCartProduct']['site_id'] = $this->Session->read('Site.id');
				$tmp['ShoppingCartProduct']['product_id'] = $productID;
				$tmp['ShoppingCartProduct']['category_id'] = $categoryID;
				$tmp['ShoppingCartProduct']['category_name'] = $categoryInfo['Category']['name'];
				$tmp['ShoppingCartProduct']['product_name'] = $productInfo['Product']['name'];
				$tmp['ShoppingCartProduct']['mrp'] = $productInfo['Product']['mrp'];
				$tmp['ShoppingCartProduct']['discount'] = $productInfo['Product']['discount'];

				// get shopping cart product details
				$shoppingCartProductInfo = $this->getShoppingCartProductDetails($tmp);
				if (!empty($shoppingCartProductInfo)) {
					$tmp['ShoppingCartProduct']['quantity'] = $data['ShoppingCartProduct']['quantity'] + $shoppingCartProductInfo['ShoppingCartProduct']['quantity'];
					$tmp['ShoppingCartProduct']['id'] = $shoppingCartProductInfo['ShoppingCartProduct']['id'];
				} else {
					$tmp['ShoppingCartProduct']['quantity'] = $data['ShoppingCartProduct']['quantity'];
					$tmp['ShoppingCartProduct']['id'] = null;
				}

				App::uses('ShoppingCartProduct', 'Model');
				$shoppingCartProductModel = new ShoppingCartProduct;
				if ($shoppingCartProductModel->save($tmp)) {
					$this->successMsg('Product successfully added to shopping list');
				} else {
					$this->successMsg('An error occurred while communicating with the server');
				}
			} else {
				$this->successMsg('An error occurred while communicating with the server');
			}
		}

		$this->redirect($this->request->referer());
	}

	/**
	 * Get shopping cart product details based on data
	 */
	function getShoppingCartProductDetails($data)
	{
		App::uses('ShoppingCartProduct', 'Model');
		$shoppingCartProductModel = new ShoppingCartProduct;

		$conditions = [
			'ShoppingCartProduct.shopping_cart_id' => $data['ShoppingCartProduct']['shopping_cart_id'],
			'ShoppingCartProduct.product_id' => $data['ShoppingCartProduct']['product_id'],
			'ShoppingCartProduct.category_id' => $data['ShoppingCartProduct']['category_id'],
		];
		$productInfo = $shoppingCartProductModel->find('first', ['conditions' => $conditions]);

		return $productInfo;
	}

	/**
	 * Function to get shopping cart products
	 */
	function getCartProducts()
	{
		$shoppingCart = null;
		if ($this->Session->check('ShoppingCart.id')) {
			$shoppingCart = $this->getShoppingCartProducts();
		}
		return $shoppingCart;
	}

	/**
	 * Function to delete shopping cart product
	 */
	function deleteShoppingCartProduct($shoppingCartProductID)
	{
		App::uses('ShoppingCartProduct', 'Model');
		$shoppingCartProductModel = new ShoppingCartProduct;

		$conditions = [
			'ShoppingCartProduct.id' => $shoppingCartProductID,
			'ShoppingCartProduct.shopping_cart_id' => $this->Session->read('ShoppingCart.id'),
		];
		if ($productInfo = $shoppingCartProductModel->find('first', ['conditions' => $conditions])) {
			$shoppingCartProductModel->delete($shoppingCartProductID);
			$this->successMsg('Product successfully deleted from shopping list');
		} else {
			$this->successMsg('Product not found in shopping list');
		}
		$this->redirect($this->request->referer());
	}

	/**
	 * Function to add product to shopping list by request quote form
	 */
	function requestQuoteForProduct($categoryID, $productID)
	{
		$errorMsg = null;

		if (!$categoryInfo = $this->isSiteCategory($categoryID)) {
			$this->Session->setFlash('Category Not Found', 'default', ['class' => 'error']);
		} else if (!$productInfo = $this->isSiteProduct($productID)) {
			$this->Session->setFlash('Product Not Found', 'default', ['class' => 'error']);
		} else if ($this->request->isPost()) {
			$data = $this->request->data;
			$shoppingCartID = $this->getShoppingCartID();

			if ($shoppingCartID) {

				$tmp['ShoppingCartProduct']['shopping_cart_id'] = $shoppingCartID;
				$tmp['ShoppingCartProduct']['site_id'] = $this->Session->read('Site.id');
				$tmp['ShoppingCartProduct']['product_id'] = $productID;
				$tmp['ShoppingCartProduct']['category_id'] = $categoryID;
				$tmp['ShoppingCartProduct']['product_name'] = $productInfo['Product']['name'];
				$tmp['ShoppingCartProduct']['category_name'] = $categoryInfo['Category']['name'];

				// get shopping cart product details
				$shoppingCartProductInfo = $this->getShoppingCartProductDetails($tmp);
				if (!empty($shoppingCartProductInfo)) {
					$tmp['ShoppingCartProduct']['quantity'] = $data['ShoppingCartProduct']['quantity'] + $shoppingCartProductInfo['ShoppingCartProduct']['quantity'];
					$tmp['ShoppingCartProduct']['id'] = $shoppingCartProductInfo['ShoppingCartProduct']['id'];
				} else {
					$tmp['ShoppingCartProduct']['quantity'] = $data['ShoppingCartProduct']['quantity'];
					$tmp['ShoppingCartProduct']['id'] = null;
				}

				App::uses('ShoppingCartProduct', 'Model');
				$shoppingCartProductModel = new ShoppingCartProduct;
				if ($shoppingCartProductModel->save($tmp)) {
					$this->Session->setFlash('Product successfully added to shopping list', 'default', ['class' => 'success']);
				} else {
					$this->Session->setFlash('An error occured while communicating with the server.', 'default', ['class' => 'error']);
					$this->redirect($this->request->referer());
				}
			} else {
				$this->Session->setFlash('An error occured while communicating with the server.', 'default', ['class' => 'error']);
				$this->redirect($this->request->referer());
			}
		}
		$this->redirect('/RequestPriceQuote');
	}
}

?>
