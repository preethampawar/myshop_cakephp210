<style type="text/css">
	.mb-3 {
		margin-bottom: 1.2rem;
	}
</style>
<div>
	<?php echo $this->Form->create(); ?>
	<h1 class="mb-3">Login</h1>

	<div class="mb-3 d-none">
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

			autofocus>
	</div>

	<div class="mb-1">
		<label for="exampleFormControlInput1" class="form-label font-weight-bold">Mobile Number
			<span class="badge bg-info" data-bs-toggle="tooltip" data-placement="top"
				  title="Enter your 10 digit mobile number without country code">?</span>
		</label>
		<input
			type="tel"
			name="data[User][mobile]"
			class="form-control"
			id="UserMobile"
			placeholder="Enter your 10 digit mobile number"
			minlength="10"
			maxlength="10"
			required
			autofocus>
	</div>
	<div class="mb-4 small text-danger">
		*OTP will be sent to the linked Email Address of your mobile number.
	</div>
	<div class="mb-3">
		<button type="submit" class="btn btn-md btn-primary">Next - Generate OTP</button>
	</div>

	<?php echo $this->Form->end(); ?>
</div>



<div class="mt-5">
	<h5>New Customer?</h5>
	<a href="/users/customerRegistration" class="link-danger">Click here to Register</a>.

</div>
