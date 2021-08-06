<div>
	<?php echo $this->Form->create(); ?>
	<h1 class="">Customer Registration</h1>

	<div class="mt-3">
		<label for="exampleFormControlInput1" class="form-label font-weight-bold">
			Email Address
			<span class="badge bg-info" data-bs-toggle="tooltip" data-placement="top"
				  title="Enter your email address. This will be used for communication purposes only">?</span>
		</label>
		<input
			type="email"
			name="data[User][email]"
			class="form-control"
			id="UserEmail"
			placeholder="Enter your email address"
			maxlength="55"
			value="<?= $email ?>"
			required
			autofocus>
	</div>

	<div class="mt-3">
		<label for="exampleFormControlInput1" class="form-label font-weight-bold">Mobile Number
			<span class="badge bg-info" data-bs-toggle="tooltip" data-placement="top"
				  title="Enter your 10 digit mobile number without country code">?</span>
		</label>
		<input
			type="number"
			name="data[User][mobile]"
			class="form-control"
			id="UserMobile"
			placeholder="Enter your 10 digit mobile number"
			minlength="10"
			maxlength="10"
			value="<?= $mobile ?>"
			required
			autofocus>
	</div>
	<div class="mt-4">
		<button type="submit" class="btn btn-md btn-primary">Next - Generate OTP</button>
		<a href="/" class="btn btn-md btn-secondary ms-3">Cancel</a>
	</div>
	<?php echo $this->Form->end(); ?>

	<div class="alert alert-warning mt-4 p-2 small">
		Note: OTP will be sent to the selected Email Address.
	</div>
</div>
