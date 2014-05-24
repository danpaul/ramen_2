<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
*/


Route::controller('user', 'UserController');

Route::get('verify/{code}', function()
{

});





Route::get('/', function()
{
	echo 'home';
});

// Route::controller('admin', 'AdminController');