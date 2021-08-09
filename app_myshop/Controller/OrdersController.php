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
		$this->checkSeller();

		$siteId = $this->Session->read('Site.id');

		$sql = 'select count(*) count, status from orders where site_id = '.$siteId.' and archived = 0 group by status';
		$ordersCountByStatus = $this->Order->query($sql);

		$sql = 'select count(*) count from orders where site_id = '.$siteId.' and archived = 1';
		$archivedOrdersCount = $this->Order->query($sql);

		$conditions = [
			'Order.site_id' => $siteId,
			'Order.archived' => 0,
		];

		switch ($orderType) {
			case Order::ORDER_STATUS_DRAFT:
			case Order::ORDER_STATUS_NEW:
			case Order::ORDER_STATUS_CONFIRMED:
			case Order::ORDER_STATUS_SHIPPED:
			case Order::ORDER_STATUS_DELIVERED:
			case Order::ORDER_STATUS_CANCELLED:
			case Order::ORDER_STATUS_CLOSED:
			case Order::ORDER_STATUS_RETURNED:
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
		$this->set('ordersCountByStatus', $ordersCountByStatus);
		$this->set('archivedOrdersCount', $archivedOrdersCount);
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

	private function registerGuestUser($mobile, $email)
	{
		$user = $this->createCustomer($mobile, $email);

		if ($user) {
			try {
				$this->sendSuccessfulEnrollmentMessage($mobile, $email);
			} catch (Exception $e) {
			}

			return $user;
		}

		return false;
	}

	public function create($autoRegister = 0)
	{
		$autoRegister = (int)$autoRegister;

		App::uses('ShoppingCart', 'Model');
		$shoppingCartModel = new ShoppingCart;

		App::uses('User', 'Model');
		$userModel = new User;

		App::uses('ShoppingCartProduct', 'Model');
		$shoppingCartProductModel = new ShoppingCartProduct;
		$error = null;

		$this->layout = false;
		$orderId = $this->getOrderId();
		$orderDetails = $this->Order->findById($orderId);

		$userId = null;

		// if the user is not logged in then auto register user based on $autoRegister flag
		if (!$this->Session->check('User.id')) {
			if ($autoRegister === 1) {
				$userEmail = $orderDetails['Order']['customer_email'];
				$userMobile = $orderDetails['Order']['customer_phone'];

				// check if guest is already registered
				$existingUser = $userModel->findByMobileAndSiteId($userMobile, $this->Session->read('Site.id'));

				if ($existingUser) {
					$userId = $existingUser['User']['id'];
				} else {
					$newUser = $this->registerGuestUser($userMobile, $userEmail);

					if ($newUser) {
						$userId = $newUser['User']['id'];
					} else {
						$error = 'Could not auto register user. Please try again.';
					}
				}
			} else {
				$error = 'Please login to place an Order';
			}
		} else {
			$userId = $this->Session->read('User.id');
		}

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

	public function admin_updateStatus($encodedOrderId, $orderStatus, $sendEmailToCustomer = null)
	{
		if (!in_array($orderStatus, Order::ORDER_STATUS_OPTIONS)) {
			$this->errorMsg('Invalid request');
			$this->redirect('/admin/orders/details/'.$encodedOrderId);
			return;
		}

		$orderId = base64_decode($encodedOrderId);
		$this->layout = false;
		$error = null;

		$siteId = $this->Session->read('Site.id');
		$conditions = ['Order.site_id' => $siteId, 'Order.id' => $orderId];
		$orderDetails = $this->Order->find('first', ['conditions'=>$conditions]);

		if ($orderDetails['Order']['archived']) {
			$this->errorMsg('This action cannot be performed on archived orders');
			$this->redirect('/admin/orders/details/'.$encodedOrderId);
		}

		$log = json_decode($orderDetails['Order']['log'], true);

		$newLog = [
			'orderStatus' => $orderStatus,
			'date' => time()
		];
		$log[] = $newLog;
		$log = json_encode($log);

		$orderData = [
			'Order' => [
				'id' => $orderId,
				'status' => $orderStatus,
				'log' => $log,
			]
		];

		if ($this->Order->save($orderData)) {
			$this->successMsg('Order status updated successfully');

			if ($sendEmailToCustomer) {
				$this->sendOrderEmail($encodedOrderId, $orderStatus, true);
			}
		} else {
			$this->errorMsg('Failed to update order status');
		}

		$this->redirect('/admin/orders/details/'.$encodedOrderId);
		exit;
	}

	public function admin_archive($encodedOrderId, $archiveText)
	{
		$orderId = base64_decode($encodedOrderId);
		$archiveText = base64_decode($archiveText);

		if ($archiveText !== Order::ORDER_ARCHIVE) {
			$this->errorMsg('Invalid request. Please try again.');
			$this->redirect($this->request->referer());
		}

		$siteId = $this->Session->read('Site.id');
		$conditions = ['Order.site_id' => $siteId, 'Order.id' => $orderId];
		$orderDetails = $this->Order->find('first', ['conditions'=>$conditions]);

		if (empty($orderDetails)) {
			$this->errorMsg('You are not authorized to perform this action.');
			$this->redirect($this->request->referer());
		}

		$this->layout = false;
		$orderData = [
			'Order' => [
				'id' => $orderId,
				'archived' => true,
			]
		];

		if ($this->Order->save($orderData)) {
			$this->successMsg('Order no. #' . $orderId . ' has been archived.');
		} else {
			$this->errorMsg('Failed to update order status');
		}

		$this->redirect($this->request->referer());
		exit;
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

	public function sendOrderEmail($encodedOrderId, $orderStatus, $return = false)
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
			case Order::ORDER_STATUS_CONFIRMED:
				$emailTemplate = 'order_confirmed';
				$subject = 'Confirmed - Order #'.$orderId;
				break;
			case Order::ORDER_STATUS_SHIPPED:
				$emailTemplate = 'order_shipped';
				$subject = 'Shipped - Order #'.$orderId;
				break;
			case Order::ORDER_STATUS_DELIVERED:
				$emailTemplate = 'order_delivered';
				$subject = 'Delivered - Order #'.$orderId;
				break;
			case Order::ORDER_STATUS_CANCELLED:
				$emailTemplate = 'order_cancelled';
				$subject = 'Cancelled - Order #'.$orderId;
				break;
			case Order::ORDER_STATUS_RETURNED:
				$emailTemplate = 'order_returned';
				$subject = 'Returned - Order #'.$orderId;
				break;
			case Order::ORDER_STATUS_CLOSED:
				$emailTemplate = 'order_closed';
				$subject = 'Closed - Order #'.$orderId;
				break;
			default:
				break;
		}

		if (!$emailTemplate) {
			$error = 'No template found';
		}

		$toName = $order['Order']['customer_name'];
		$toEmail = $order['Order']['customer_email'];
		$bccEmails = $this->getBccEmails();
		$noReply = $this->getNoReplyEmail();

		$Email = new CakeEmail('smtpNoReply');
		$Email->viewVars(array('order' => $order));
		$Email->template($emailTemplate, 'default')
			->emailFormat('html')
			->to([$toEmail => $toName])
			->from([$noReply['fromEmail'] => $noReply['fromName']])
			->bcc($bccEmails)
			->subject($subject)
			->send();

		$this->set('error', $error);

		if ($return) {
			return $error;
		}
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
