<?php

class UserController extends BaseController {

	public function getLogin()
	{
		return View::make('userLogin');
	}

	public function postLogin()
	{
		echo Input::get('email');
		echo Input::get('password');
	}

	public function getRegister()
	{
		return View::make('userRegister');
	}

	public function postRegister()
	{
		// get input params
		$userParams = array(
			'email' => Input::get('email', ''),
			'password' => Input::get('password', ''),
			'password_confirmation' => Input::get('password_confirmation', '')
		);

		//perform validation
		$rules = array(
			'email' => 'required|email|unique:users',
			'password' => 'required|min:8|confirmed'
		);

		$validator = Validator::make($userParams, $rules);

		if( $validator->fails() )
		{
			return Redirect::to('/user/login')->withErrors($validator);
		}

		// save user
		$user = new User;

		$user->email = $userParams['email'];
		$user->password = Hash::make($userParams['password']);

		$user->save();

		// create verification record and link
		$verification = new Verification;

		$verification->code = md5(rand()). md5(rand());
		$verification->email = $user->email;

		$verification->save();		

		//send welcome email
		$verificationLink = URL::to('user/verify/'. $verification->code);

		Mail::send(
			'emails.welcome',
			array('verificationLink' => $verificationLink),
			function($message) use ($user)
		{
			$message->to($user->email)->subject('Welcome!');
		});


	}

}

		// Validate






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
// var_dump('in registration');
		// return View::make('userRegister');
	

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


