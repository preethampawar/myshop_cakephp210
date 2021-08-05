<?php
App::uses('CakeEmail', 'Network/Email');
App::uses('Order', 'Model');

class OrdersController extends AppController
{

	public function beforeFilter()
	{
		parent::beforeFilter();
	}

	public function index()
	{
		$conditions = [
			'Order.site_id' => $this->Session->read('Site.id'),
			'Order.user_id' => $this->Session->read('User.id'),
			'Order.status !=' => Order::ORDER_STATUS_DRAFT,
		];

		$this->Order->unbindModel(['hasMany' => ['OrderProduct']]);

		$this->paginate = [
			'limit' => 10,
			'order' => ['Order.created' => 'DESC'],
			'conditions' => $conditions,
		];
		$orders = $this->paginate();

		$this->set('orders', $orders);
	}

	public function admin_index($orderType = null)
	{
		$conditions = [
			'Order.site_id' => $this->Session->read('Site.id'),
		];

		switch ($orderType) {
			case Order::ORDER_STATUS_DRAFT:
			case Order::ORDER_STATUS_NEW:
			case Order::ORDER_STATUS_CONFIRMED:
			case Order::ORDER_STATUS_SHIPPED:
			case Order::ORDER_STATUS_DELIVERED:
			case Order::ORDER_STATUS_CANCELLED:
			case Order::ORDER_STATUS_CLOSED:
				break;
			default:
				$orderType = Order::ORDER_STATUS_NEW;
				break;
		}

		$conditions['Order.status'] = $orderType;

		$this->Order->bindModel(['belongsTo' => ['User']]);
		$this->Order->unbindModel(['hasMany' => ['OrderProduct']]);

		$this->paginate = [
			'limit' => 100,
			'order' => ['Order.created' => 'DESC'],
			'conditions' => $conditions,
		];
		$orders = $this->paginate();

		$this->set('orderType', $orderType);
		$this->set('orders', $orders);
	}

	public function details($encodedOrderId)
	{
		$orderId = base64_decode($encodedOrderId);
		$order = $this->Order->findById($orderId);

		$this->set('order', $order);
	}

	public function admin_details($encodedOrderId)
	{
		$orderId = base64_decode($encodedOrderId);
		$order = $this->Order->findById($orderId);

		$this->set('order', $order);
	}

	public function create()
	{
		App::uses('ShoppingCart', 'Model');
		$shoppingCartModel = new ShoppingCart;
		App::uses('ShoppingCartProduct', 'Model');
		$shoppingCartProductModel = new ShoppingCartProduct;
		$error = null;

		if (!$this->Session->check('User.id')) {
			$error = 'Please login to place an Order';
		}

		$this->layout = false;
		$orderId = $this->getOrderId();
		$orderDetails = $this->Order->findById($orderId);
		$orderEmailUrl = '/orders/sendOrderEmail/' . base64_encode($orderId) . '/NEW';

		$log = json_decode($orderDetails['Order']['log'], true);

		$data = $this->request->input('json_decode', true);
		$msg = '';
		$siteId = $this->Session->read('Site.id');
		$shoppingCartId = $this->getShoppingCartID();
		$cartValue = 0;
		$totalItems = 0;
		$totalDiscount = 0;
		$totalTax = 0;
		$payableAmount = 0;
		$shippingAmount = $this->Session->read('Site.shipping_charges');
		$orderStatus = Order::ORDER_STATUS_NEW;
		$newLog = [
			'orderStatus' => $orderStatus,
			'date' => time()
		];
		$log[] = $newLog;
		$log = json_encode($log);
		$userId = $this->Session->read('User.id');

		if (!$error) {
			$error = isset($data['confirmed']) && $data['confirmed'] == 1 ? null : 'Invalid request (OR) Your session has timed out.';
		}

		if (!$error) {
			$shoppingCartProducts = $shoppingCartModel->getShoppingCartProducts($shoppingCartId);

			if (empty($shoppingCartProducts['ShoppingCartProduct'])) {
				$error = 'There are no items in your cart (OR) Your session has timed out. Please try again.';
			}
		}

		if (!$error) {
			foreach ($shoppingCartProducts['ShoppingCartProduct'] as $row) {
				$qty = $row['quantity'] ?: 0;
				$mrp = $row['mrp'];
				$discount = $row['discount'];
				$salePrice = $mrp - $discount;
				$totalProductPurchaseValue = $salePrice * $qty;
				$cartValue += $totalProductPurchaseValue;
				$totalItems += $qty;
				$totalDiscount += $discount * $qty;
			}

			$payableAmount = $cartValue + $this->Session->read('Site.shipping_charges');

			$orderData = [
				'Order' => [
					'id' => $orderId,
					'total_cart_value' => $cartValue,
					'total_items' => $totalItems,
					'total_discount' => $totalDiscount,
					'shipping_amount' => $shippingAmount,
					'total_tax' => $totalTax,
					'total_order_amount' => $payableAmount,
					'status' => $orderStatus,
					'log' => $log,
					'notes' => null,
					'user_id' => $userId,
				]
			];

			if ($orderDetails = $this->Order->save($orderData)) {

				if ($this->saveOrderProducts($shoppingCartProducts['ShoppingCartProduct'], $orderId)) {
					// delete shopping cart details
					$this->Session->delete('ShoppingCart');
					$this->Session->delete('Order');
					$shoppingCartModel->delete($shoppingCartId);
					$shoppingCartProductModel->deleteAll(['ShoppingCartProduct.shopping_cart_id' => $shoppingCartId]);
					$msg = 'Your order has been placed successfully. You will be notified once the order is confirmed.';
				} else {
					// delete Order as OrderProducts could not be saved
					$this->Order->delete($orderId);
					$error = 'Order details could not be saved. Please try again.';
				}
			} else {
				$error = 'Order could not be saved. Please try again.';
			}
		}

		$this->set('error', $error);
		$this->set('msg', $msg);
		$this->set('orderEmailUrl', $orderEmailUrl);
	}

	private function saveOrderProducts($shoppingCartProducts, $orderId)
	{
		App::uses('OrderProduct', 'Model');
		$orderProductModel = new OrderProduct();
		$error = false;
		$siteId = $this->Session->read('Site.id');

		foreach ($shoppingCartProducts as $row) {
			$qty = $row['quantity'] ?: 0;
			$mrp = $row['mrp'];
			$discount = $row['discount'];
			$salePrice = $mrp - $discount;
			$productName = $row['Product']['name'];
			$categoryName = $row['Category']['name'];

			$orderProductData = null;
			$orderProductData = [
				'OrderProduct' => [
					'id' => null,
					'order_id' => $orderId,
					'site_id' => $siteId,
					'product_name' => $productName,
					'category_name' => $categoryName,
					'quantity' => $qty,
					'mrp' => $mrp,
					'discount' => $discount,
					'sale_price' => $salePrice,
				]
			];



			if (!$orderProductModel->save($orderProductData)) {
				$error = true;
				break;
			}
		}

		if ($error) {
			// delete previously saved order products
			$conditions = ['OrderProduct.order_id' => $orderId];
			$orderProductModel->deleteAll($conditions, false);

			return false;
		}

		return true;
	}

	public function sendOrderEmail($encodedOrderId, $orderStatus)
	{
		$this->layout = false;
		$emailTemplate = null;
		$subject = null;
		$error = null;
		$orderId = base64_decode($encodedOrderId);
		$order = $this->Order->findById($orderId);

		switch($orderStatus) {
			case Order::ORDER_STATUS_NEW:
				$emailTemplate = 'order_new';
				$subject = 'New Order #'.$orderId;
				break;
			default:
				break;
		}

		if (!$emailTemplate) {
			$error = 'No template found';
		}

		$toName = $order['Order']['customer_name'];
		$toEmail = $order['Order']['customer_email'];
		$adminEmail = Configure::read('AdminEmail');
		$storeAdminEmail = $this->Session->read('User.email');
		$bccEmail = [$adminEmail, $storeAdminEmail];

		$Email = new CakeEmail('smtpNoReply');
		$Email->viewVars(array('order' => $order));
		$Email->template('order_new', 'default')
			->emailFormat('html')
			->to([$toEmail => $toName])
			->from([$this->noReplyEmail['fromEmail'] => $this->noReplyEmail['fromName']])
			->bcc($bccEmail)
			->subject($subject)
			->send();

		$this->set('error', $error);
	}

	private function validatePaymentDetails($data)
	{
		$paymentMethods = [Order::PAYMENT_METHOD_COD, Order::PAYMENT_METHOD_GPAY, Order::PAYMENT_METHOD_PHONE_PE, Order::PAYMENT_METHOD_PAYTM];

		if (empty($data['paymentMethod'])) {
			return 'Please select payment method';
		}

		if (!empty($data['paymentMethod']) && !in_array($data['paymentMethod'], $paymentMethods)) {
			return 'Invalid Payment Method';
		}

		if (!empty($data['paymentMethod'])
			&& $data['paymentMethod'] != Order::PAYMENT_METHOD_COD
			&& empty($data['paymentReferenceNo'])) {
			return 'Payment reference no. is required';
		}

		return null;
	}

	private function validateDeliveryDetails($data)
	{
		if (empty($data['customerName'])) {
			return 'Contact Name is required';
		}
		if (empty($data['customerPhone'])) {
			return 'Contact Phone no. is required';
		}
		if (empty($data['customerAddress'])) {
			return 'Delivery Address is required';
		}

		return null;
	}

	public function saveOrderDeliveryDetails()
	{
		$this->layout = false;

		$data = $this->request->input('json_decode', true);
		$error = $this->validateDeliveryDetails($data);

		if (!$error) {
			$orderId = $this->getOrderId();
			$orderData = [
				'Order' => [
					'id' => $orderId,
					'customer_name' => $data['customerName'],
					'customer_phone' => $data['customerPhone'],
					'customer_email' => $data['customerEmail'],
					'customer_address' => $data['customerAddress'],
					'customer_message' => $data['customerMessage'],
				]
			];

			if (! $this->Order->save($orderData)) {
				$error = 'Delivery details could not be saved. Please try again.';
			}
		}

		$this->set('error', $error);
	}

	public function loadOrderPaymentDetails()
	{
		$this->layout = false;

		App::uses('ShoppingCart', 'Model');
		$shoppingCartModel = new ShoppingCart();

		$shoppingCartProducts = $shoppingCartModel->getShoppingCartProducts($this->getShoppingCartID());

		$orderDetails = $this->Order->findById($this->getOrderId());

		$this->set('shoppingCartProducts', $shoppingCartProducts);
		$this->set('orderDetails', $orderDetails);
	}


	public function saveOrderPaymentDetails()
	{
		$this->layout = false;

		$data = $this->request->input('json_decode', true);
		$error = $this->validatePaymentDetails($data);

		if (!$error) {
			$orderId = $this->getOrderId();
			$orderData = [
				'Order' => [
					'id' => $orderId,
					'payment_method' => $data['paymentMethod'],
					'payment_reference_no' => $data['paymentReferenceNo'],
				]
			];

			if (! $this->Order->save($orderData)) {
				$error = 'Payment details could not be saved. Please try again.';
			}
		}

		$this->set('error', $error);
	}

}
?>
