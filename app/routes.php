<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
*/




Route::get('test', function(){
	dd(User::isAdmin());
});





Route::controller('user', 'UserController');

Route::controller('password', 'RemindersController');

Route::controller('product', 'ProductController');

Route::controller('catalog', 'CatalogController');

Route::controller('cart', 'CartController');

Route::controller('admin/taxonomy', 'TaxonomyController');

Route::controller('admin/product', 'ProductController');

Route::get('verify/{code}', function(){ });





Route::get('/', function()
{
	return CatalogController::getHome();
});