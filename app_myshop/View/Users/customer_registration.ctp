<div>
	<?php echo $this->Form->create(); ?>
	<h1 class="">Customer Registration</h1>

	<div class="mt-3 text-end">
		<a href="/" class="btn btn-sm btn-outline-secondary">Cancel</a>
	</div>
	<div class="mt-3">
		<label for="exampleFormControlInput1" class="form-label font-weight-bold">
			Email Address <span class="text-danger small">(required)</span>
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
		<label for="exampleFormControlInput1" class="form-label font-weight-bold">
			Mobile Number <span class="text-danger small">(required)</span>
		</label>
		<input
			type="number"
			name="data[User][mobile]"
			class="form-control"
			id="UserMobile"
			placeholder="Enter your 10 digit mobile number ex: 9494555588."
			min="6000000000"
			max="9999999999"
			value="<?= $mobile ?>"
			required
			autofocus>
	</div>

	<div class="mt-3 small text-danger">
		*OTP will be sent to your Email Address.
	</div>
	<div class="mt-4">
		<button type="submit" class="btn btn-md btn-primary">Next - Generate OTP</button>

	</div>
	<?php echo $this->Form->end(); ?>

</div>
