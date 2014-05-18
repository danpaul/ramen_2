<form
	action="/user/login"
	method="post"
>
	<fieldset>
		<legend>Login</legend>
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
		<input class="button small radius" type="submit" value="Login">
		<br>
		<!-- <small><a href="/user/reset-password">Forgot your username/password?</a></small> -->
	</fieldset>
</form>