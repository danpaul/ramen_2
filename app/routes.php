<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
*/


Route::controller('user', 'UserController');

Route::get('/admin/product/add', function()
{
	return Category::all();
	// echo 'home';
});

Route::get('/', function()
{
	return Category::all();
	// echo 'home';
});

Route::controller('admin', 'AdminController');