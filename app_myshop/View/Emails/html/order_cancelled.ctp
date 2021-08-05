<p>Dear <?= $order['Order']['customer_name']?>,</p>

<p>Your Order No. #<?= $order['Order']['id']; ?> has been cancelled.</p>

<p>Thank you for shopping with us.</p>

<p>
	-<br>
	<?= $this->Session->read('Site.title') ?><br>
	<?= $this->Html->url('/', true) ?>
</p>

<p>This is an auto generated email. Please do not respond.</p>
