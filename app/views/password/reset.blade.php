<?php foreach( $errors->all() as $error ){ ?>

	<p><?php echo $error ?></p>

<?php } ?>

<form action="{{ action('RemindersController@postReset') }}" method="POST">
    <input type="hidden" name="token" value="{{ $token }}">
    Email: <input type="email" name="email">
    Password: <input type="password" name="password">
    Confirm Password: <input type="password" name="password_confirmation">
    <input type="submit" value="Reset Password">
</form>