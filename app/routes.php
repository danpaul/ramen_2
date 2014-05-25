<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
*/


Route::controller('user', 'UserController');

Route::controller('password', 'RemindersController');

Route::controller('product', 'ProductController');

Route::controller('admin/taxonomy', 'TaxonomyController');

Route::get('verify/{code}', function(){ });





Route::get('/', function()
{
	echo 'home';
});

// Route::controller('admin', 'AdminController');