<?php

class ProductController extends BaseController {

/*******************************************************************************

	ADMIN RESTRICTED ROUTES

*******************************************************************************/

	public function getAll()
	{
		return View::make('product.all', array('products' => Product::all()));
	}

	public function getEdit($id)
	{

		$product = Product::find($id); 
		return View::make(
			'product.edit',
			array(
				'product' => $product,
				'tags' => Tag::getAll(),
				'categoryTrees' => Category::getTrees(),
				'categoryLists' => Category::getLists(),
				'productImages' => $product->productImages
			)
		);
	}

	public function getAdd()
	{
		return View::make('product.add');
	}

	public function postAdd()
	{
		Product::create(Input::all());
		return Redirect::action('ProductController@getAll');
	}

	public function postUpdate($id)
	{
		if( !$product = Product::find($id) )
		{
			return Redirect::action('ProductController@getAll')
				->withErrors('Invalid product id.');
		}
		$product->fill(Input::all());
		$product->save();
		return Redirect::action('ProductController@getAll')
				->with('messages', array('Your product has been updated.'));
	}

	public function postUpdateCategories($id)
	{
		if( !$product = Product::find($id) )
		{
			return Redirect::back()->withErrors('Invalid id.');
		}

		$product->categories()->sync(Input::get('ids', array()));
		return Redirect::back();
	}

	public function postUpdateTags($id)
	{
		if( !$product = Product::find($id) )
		{
			return Redirect::back()->withErrors('Invalid id.');
		}

		$product->tags()->sync(Input::get('ids', array()));
		return Redirect::back();
	}

	public function postAddImages($id)
	{
		if( !$product = Product::find($id) )
		{
			return Redirect::back()->withErrors('Invalid id.');
		}

		// filter out deleted
		$newImages = array_filter(Input::get('image'), 'self::isDeleted');
		$productImages = array();

		foreach( $newImages as $image )
		{
			array_push($productImages, new ProductImage($image));
		}

		$product->productImages()->delete();
		$product->productImages()->saveMany($productImages);

		return Redirect::back();

	}

	private static function isDeleted($field)
	{
		return( !isset($field['delete']) );
	}

}