<?php

class ProductController extends BaseController {

	public function getAll()
	{
		return View::make('product.all', array('products' => Product::all()));
	}

	public function getEdit($id)
	{
		return View::make('product.edit', array('product' => Product::find($id)));

	}

	public function getAdd()
	{
		return View::make('product.add');
	}

	public function postAdd()
	{
		$product = new Product();

		$product->name = Input::get('name', '');
		$product->sku = Input::get('sku', '');
		$product->description = Input::get('description', '');
		$product->price = Input::get('price', 0.00);
		$product->inventory = Input::get('inventory', 0);
		$product->save();

		return Redirect::action('ProductController@getAll');
	}

	public function postUpdate()
	{
		$product = new Product(Input::all());
		$product->save();
		return Redirect::action('ProductController@getAll');
	}
}