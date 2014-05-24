<?php

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;

class Verification extends Eloquent {

	public function user()
	{
		return $this->belongsTo('User');
	}

}