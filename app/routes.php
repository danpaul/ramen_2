<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
*/


Route::get('/', function()
{
	return Category::all();
	// echo 'home';
});

Route::controller('admin', 'AdminController');