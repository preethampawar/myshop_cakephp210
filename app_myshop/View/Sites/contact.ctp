<div>
	<h5>Contact Details</h5>
	<br>
	<?php
	echo $this->Session->read('Site.contact_info');
	?>
</div>


<?php
$pageUrl = $this->Html->url($this->request->here, true);
$customMeta = '';
$customMeta .= $this->Html->meta(['property' => 'og:url', 'content' => $pageUrl, 'inline' => false]);
$customMeta .= $this->Html->meta(['property' => 'og:type', 'content' => 'website', 'inline' => false]);
$customMeta .= $this->Html->meta(['property' => 'og:title', 'content' => 'Contact Us', 'inline' => false]);
$customMeta .= $this->Html->meta(['property' => 'og:description', 'content' => strip_tags($this->Session->read('Site.contact_info')), 'inline' => false]);
// $customMeta .= ($productImageUrl) ? $this->Html->meta(['property' => 'og:image', 'content' => $productImageUrl, 'inline' => false]) : '';
$customMeta .= $this->Html->meta(['property' => 'og:site_name', 'content' => $this->Session->read('Site.title'), 'inline' => false]);

$this->set('customMeta', $customMeta);
$this->set('title_for_layout', 'Contact Us');
?>
