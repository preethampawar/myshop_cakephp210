<div class="fixed-top" style="width:16rem; left: auto; margin-top: 8rem;">
	<div id="<?php echo h($key) ?>Message" class="d-flex justify-content-between message success alert text-white bg-primary alert-dismissible p-2 shadow border-white">
		<div class="mr-3">
			<span class="fa fa-check-circle"></span>
			<?= h($message) ?>
		</div>
		<div>
			<span class="fa fa-close text-white" role="button" data-dismiss="alert" aria-label="Close"></span>
		</div>
	</div>
</div>
