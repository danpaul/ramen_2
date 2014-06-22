<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
*/

Route::get('test', function(){
	// Cart::associate('Product')->add('5', 'foo product', 2, 3.33);
	// dd(Cart::content());

$content = Cart::content();

foreach($content as $row)
{
    var_dump($row);
}

	$item = Cart::get('468399581342505c47f4615b81bedaa9');

	dd($item->product->description);
	// dd($item->product);


});

Route::controller('user', 'UserController');

Route::controller('password', 'RemindersController');

Route::controller('product', 'ProductController');

Route::controller('admin/taxonomy', 'TaxonomyController');

Route::controller('admin/product', 'ProductController');

Route::get('verify/{code}', function(){ });





Route::get('/', function()
{
	echo 'home';
});

// Route::controller('admin', 'AdminController');