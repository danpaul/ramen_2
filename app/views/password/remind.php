<?php foreach( $errors->all() as $error ){ ?>

	<p><?php echo $error ?></p>

<?php } ?>

<form action="/password/remind" method="POST">
    <input type="email" name="email">
    <input type="submit" value="Send Reminder">
</form>