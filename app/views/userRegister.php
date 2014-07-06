<?php foreach( $errors->all() as $error ){ ?>

	<p><?php echo $error ?></p>

<?php } ?>

<form
	action="/user/register"
	method="post"
>
	<fieldset>
		<legend>Register</legend>
		<label>Email</label>
		<input
			type="text"
			name="email"
		>
		<label>Password</label>
		<input
			type="password"
			name="password"
		>
		<label>Re-enter password</label>
		<input
			type="password"
			name="password_confirmation"
		>
		<input class="button small radius" type="submit" value="Register">
	</fieldset>
</form>