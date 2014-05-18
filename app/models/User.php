<?php

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password', 'role');

	/**
	 * Get the unique identifier for the user.
	 *
	 * @return mixed
	 */
	public function getAuthIdentifier()
	{
		return $this->getKey();
	}

	/**
	 * Get the password for the user.
	 *
	 * @return string
	 */
	public function getAuthPassword()
	{
		return $this->password;
	}

	/**
	 * Get the token value for the "remember me" session.
	 *
	 * @return string
	 */
	public function getRememberToken()
	{
		return $this->remember_token;
	}

	/**
	 * Set the token value for the "remember me" session.
	 *
	 * @param  string  $value
	 * @return void
	 */
	public function setRememberToken($value)
	{
		$this->remember_token = $value;
	}

	/**
	 * Get the column name for the "remember me" token.
	 *
	 * @return string
	 */
	public function getRememberTokenName()
	{
		return 'remember_token';
	}

	/**
	 * Get the e-mail address where password reminders are sent.
	 *
	 * @return string
	 */
	public function getReminderEmail()
	{
		return $this->email;
	}

	public static function registerUser($email, $password1, $password2)
	{

		echo 'in register';







		// $valid = TRUE;

		// if( $this->get_user($email) )
		// {
		// 	$valid = FALSE;
		// 	array_push($this->error_message, self::ERROR_USER_EXISTS);
		// }

		// if( !filter_var($email, FILTER_VALIDATE_EMAIL ))
		// {
		// 	$valid = FALSE;
		// 	array_push($this->error_message, self::ERROR_INVALID_EMAIL);
		// }

		// if( !$this->validate_password($password_1, $password_2) )
		// {
		// 	$valid = FALSE;
		// }









		// if( !$this->validate_registration_information($email, $password_1, $password_2) )
		// {
		// 	return FALSE;
		// }

		// $params['email'] = $email;
		// $params['salt'] = $this->get_salt();
		// $params['password'] = $this->hash_password($password_1, $params['salt']);

		// $statement = $this->db->prepare(self::STATEMENT_INSERT_USER);
		
		// if(!$statement->execute($params))
		// {
		// 	array_push($this->error_message, self::ERROR_DATABASE);
		// 	return FALSE;
		// }

		// $code = $this->get_code($email);
		// $this->verification_code = $code;
		// $verification_params = array('code' => $code, 'email' => $params['email']);
		// $statement = $this->db->prepare(self::STATEMENT_INSERT_VERIFICATION);

		// if(!$statement->execute($verification_params))
		// {
		// 	array_push($this->error_message, self::ERROR_DATABASE);
		// 	return FALSE;
		// }

		// $this->set_user($email);

		// return TRUE;




	}

}
