<?php
App::uses('AppModel', 'Model');

class Order extends AppModel
{
	const ORDER_STATUS_DRAFT = 'DRAFT';
	const ORDER_STATUS_NEW = 'NEW';
	const ORDER_STATUS_CONFIRMED = 'CONFIRMED';
	const ORDER_STATUS_SHIPPED = 'SHIPPED';
	const ORDER_STATUS_DELIVERED = 'DELIVERED';
	const ORDER_STATUS_CLOSED = 'CLOSED';
	const ORDER_STATUS_CANCELLED = 'CANCELLED';
	const PAYMENT_METHOD_COD = 'COD';
	const PAYMENT_METHOD_GPAY = 'GPAY';
	const PAYMENT_METHOD_PHONE_PE = 'PHONE_PE';
	const PAYMENT_METHOD_PAYTM = 'PAYTM';

	public $name = 'Order';

	var $hasMany = ['OrderProduct'];
}

?>
