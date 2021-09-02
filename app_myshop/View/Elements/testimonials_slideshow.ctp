<?php
$slideshowEnabled = (int)$this->Session->read('Site.show_testimonials') === 1;

if($slideshowEnabled && $this->request->params['action'] === 'display' && $this->request->params['pass'][0] === 'home') {
?>

		<?php
		$siteId = $this->Session->read('Site.id');

		App::uses('Testimonial', 'Model');
		$testimonialModel = new Testimonial();
		$conditions = [
			'Testimonial.site_id' => $siteId,
			'Testimonial.active' => 1,
		];
		$fields = [
				'Testimonial.id',
				'Testimonial.title',
				'Testimonial.customer_name',
				'Testimonial.url',
		];
		$testimonials = $testimonialModel->find('all', ['conditions' => $conditions, 'fields'=>$fields, 'order'=>'Testimonial.created DESC', 'recursive'=> -1]);
		$slideShowImages = [];

		if ($testimonials) {
			$i = 0;
			foreach ($testimonials as $testimonial) {

				$testimonialId = $testimonial['Testimonial']['id'];
				$title = $testimonial['Testimonial']['title'];
				$customerName = $testimonial['Testimonial']['customer_name'];
				$url = $testimonial['Testimonial']['url'];

				$slideShowImages[$i]['testimonialId'] = $testimonialId;
				$slideShowImages[$i]['title'] = htmlentities(trim($title));
				$slideShowImages[$i]['customerName'] = htmlentities(trim($customerName));
				$slideShowImages[$i]['linkUrl'] = $url;

				$i++;
			}
		}
	?>

	<?php
	if ($slideShowImages) {
	?>

	<h4 class="text-decoration-underline text-center mt-4">Testimonials</h4>
	<div class="mb-4 pt-3 px-3 alert-warning p-0">
		<div id="testimonialSlideShow" class="carousel carousel-dark slide" data-bs-ride="carousel">

			<div class="carousel-inner">
				<?php
				$i = 0;
				foreach($slideShowImages  as $row) {
					$testimonialId = $row['testimonialId'];
					$title = $row['title'];
					$customerName = $row['customerName'];
					$linkUrl = $row['linkUrl'];
					?>
					<div class="carousel-item <?= $i === 0 ? 'active' : '' ?>" data-bs-interval="5000">
						<div class="container p-3 text-center">
							<i class="fa fa-quote-left small text-orange me-2"></i>
							<?php
							if ($linkUrl) {
								?>
								<a href="<?= $linkUrl ?>" title="<?= $title ?>" class="text-decoration-none text-dark">
									<?= $title ?>
								</a>
								<?php
							} else {
								?>
								<?= $title ?>
								<?php
							}
							?>
							<i class="fa fa-quote-right small text-orange ms-2"></i>

							<?php
							if ($customerName) {
								?>
								<div class="text-center mt-2 fst-italic text-orange small">
									<?= $customerName ?>
								</div>
								<?php
							}
							?>
							<div class="mt-1 small"><a href="/testimonials/" class="small">Show All</a></div>
						</div>
					</div>
					<?php
					$i++;
				}
				?>
				<button class="carousel-control-prev" type="button" data-bs-target="#testimonialSlideShow" data-bs-slide="prev">
					<span class="carousel-control-prev-icon" aria-hidden="true"></span>
					<span class="visually-hidden">Previous</span>
				</button>
				<button class="carousel-control-next" type="button" data-bs-target="#testimonialSlideShow" data-bs-slide="next">
					<span class="carousel-control-next-icon" aria-hidden="true"></span>
					<span class="visually-hidden">Next</span>
				</button>

			</div>
		</div>
	</div>

<?php } ?>


<?php
}
?>
