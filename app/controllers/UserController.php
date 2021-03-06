<?php

class UserController extends BaseController {

	public function getLogin()
	{
		return View::make('userLogin');
	}

	public function getLogout()
	{
		Auth::logout();
		return Redirect::to('user/login');
	}

	public function postLogin()
	{
		if( (Auth::attempt(array(
			'email' => Input::get('email', ''),
			'password' => Input::get('password', '')))) )
		{
			return Redirect::intended('/');
		}else{
			return Redirect::to('user/login')->withErrors(
				'Invalid username and/or password.'
			);
		}
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
			// return Redirect::to('/user/login')->withErrors($validator);
			return Redirect::back()->withErrors($validator);
		}

		// save user
		$user = new User;

		$user->email = $userParams['email'];
		$user->password = Hash::make($userParams['password']);

		$user->save();

		// create verification record and link
		$verification = new Verification;

		$verification->code = md5(rand()). md5(time());
		$verification->user_id = $user->id;

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

		return View::make('notify', array('messages' => array('Thank you for registering. Please check your email to verify your email address.')));
	}

	public function getVerify($code)
	{
		$messages = array();

		//check if verification code is in DB
		$verification = Verification::where('code', '=', $code)->first();

		if( $verification === NULL )
		{
			array_push($messages, 'Verification code not found.');
			return View::make('notify', array('messages' => $messages));
		}

		$user = $verification->user;
		$user->verified = true;
		$user->save();

		//render notification page
		array_push($messages, 'Thanks, your email is now verified!');
		return View::make('notify', array('messages' => $messages));		
	}

	public function getCheckout()
	{
		Product::updateCart();
		return View::make(
			'checkout.review', array('cartContents' => Cart::content())
		)
			->nest('menu', 'partials.main_menu', array(
				'categoryTrees' => Category::getTrees(),
				'categoryLists' => Category::getLists()
			));
	}

	public function getConfirmOrder()
	{
		if( !Product::updateCart() )
		{
			Redirect::back()->withErrors(
				'Sorry, a price may have changed. Please confirm your order.'
			);
		}

		Product::updateCart();
		return View::make(
			'checkout.confirm', array('cartContents' => Cart::content())
		)
			->nest('menu', 'partials.main_menu', array(
				'categoryTrees' => Category::getTrees(),
				'categoryLists' => Category::getLists()
			));
	}

}
