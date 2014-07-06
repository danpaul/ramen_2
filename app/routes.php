<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
*/




Route::get('test', function(){
	// dd(Auth::check());
	// dd(Auth::user()->isAdmin());
	dd(User::isAdmin());
	// dd(Auth::user());
});




Route::controller('user', 'UserController');

Route::controller('password', 'RemindersController');

Route::controller('product', 'ProductController');

Route::controller('admin/taxonomy', 'TaxonomyController');

Route::controller('admin/product', 'ProductController');

Route::get('verify/{code}', function(){ });





Route::get('/', function()
{
	return CatalogController::getHome();
	// echo 'home';
});

// Route::controller('admin', 'AdminController');