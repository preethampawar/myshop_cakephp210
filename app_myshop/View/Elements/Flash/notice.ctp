<div class="fixed-top" style="width:16rem; left: auto; margin-top: 8rem;">
	<div id="<?php echo h($key) ?>Message" class="d-flex justify-content-between message notice alert text-white bg-warning alert-dismissible p-2 shadow border-white">
		<div class="mr-3">
			<span class="fa fa-check-circle"></span>
			<?= h($message) ?>
		</div>
		<div>
			<span class="fa fa-close" role="button" data-dismiss="alert" aria-label="Close"></span>
		</div>
	</div>
</div>
